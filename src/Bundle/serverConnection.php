<?php

namespace App\Bundle;

use Doctrine\ORM\EntityManager;
use App\Entity\LinuxAccounts;
use Exception;

// @TODO rework for better use with symfony

class serverConnection
{

    private $user = null;
    private $connection = null;
    private $output = '';
    private $serverPath = '/';
    private $em = null;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function setUser(int $id) : void
    {
        $la = $this->em->getRepository(LinuxAccounts::class);
        $this->user = $la->find($id);
    }


    /**
     * tries if connection is working
     * @return bool
     */
    public function tryConnect()
    {
        $connect = ssh2_connect($this->user->getHost());
        if ($connect === false) {
            return false;
        }

        ssh2_disconnect($connect);
        return true;
    }


    /**
     * connects to the server with the account
     * don´t forget to use disconnect() after finishing task
     * @throws Exception
     */
    public function connect()
    {
        $this->connection = ssh2_connect($this->user->getHost());
        if ($this->connection === false) {
            throw new Exception('error_connection', 1);
        }

        $authSuccess = false;
        if (empty($this->user->getPrivateKey()) === false) {
            $authSuccess = ssh2_auth_pubkey_file($this->connection, $this->user->getUsername(), $this->user->getPublicKey(), $this->user->getPrivateKey(), $this->user->getPassphrase());
        }

        if (!$authSuccess && empty($this->user->getPassword()) === false) {
            $authSuccess = ssh2_auth_password($this->connection, $this->user->getUsername(), $this->user->getPassword());
        }

        if ($authSuccess) {
            return $this;
        } else {
            throw new Exception('error_auth', 2);
        }
    }


    public function exec($cmd)
    {
        if (empty($this->connection) === true) {
            throw new Exception('error_no_connection', 3);
        }
        $stream = ssh2_exec($this->connection, 'cd '. $this->serverPath .'; '. $cmd);
        stream_set_blocking($stream, true);
        $stream = stream_get_contents($stream);
        $this->output = $stream;
        return $this;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function gotoServerFolder($folder)
    {
        if (substr($folder, 0, 1) == '/') {
            if (substr($folder, -1) == '/') {
                $this->serverPath = substr($folder, 0, -1);
            } else {
                $this->serverPath = $folder;
            }
        } elseif ($folder == '..') {
            $ex = explode('/', $this->serverPath);
            if (count($ex) >= 2) {
                unset($ex[count($ex)-1]);
                $this->serverPath = implode('/', $ex);
            }
        } elseif (substr($folder, 0, 1) == '.') {
            $folder = substr($folder, 1);
            $this->serverPath = $this->serverPath .'/'. $folder;
        } else {
            $this->serverPath = $this->serverPath .'/'. $folder;
        }
        return $this;
    }

    public function listFolders()
    {
        $ret = array();
        $res = $this->exec('ls -d */');
        $res = explode(PHP_EOL, $res);
        foreach ($res as $k => $v) {
            if (empty($v) === false) {
                $ret[] = str_replace('/', '', $v);
            }
        }
        return $ret;
    }

    public function listFiles()
    {
        $ret = array();
        $res = $this->exec('ls -p | grep -v /');
        $res = explode(PHP_EOL, $res);
        foreach ($res as $k => $v) {
            if (empty($v) === false) {
                $ret[] = $v;
            }
        }
        return $ret;
    }

    /**
     * lädt eine Datei in das ausgewählte Verzeichnis im Server hoch
     * @param string $file !Absoluter! Pfad zur Datei
     * @param string $targetFile Dateiname auf dem Server
     */
    public function uploadToServer($file, $targetFile = null)
    {
        if (file_exists($file) === false) {
            return false;
        }
        if (strpos($file, '/') === false) {
            return false;
        }
        if (empty($targetFile) === true) {
            $ex = explode('/', $file);
            $targetFile = end($ex);
        }

        ssh2_scp_send($this->connection, $file, $this->serverPath .'/'. $targetFile, 0666);
        return true;
    }


    public function downloadFromServer($filename, $localTarget = './getFile')
    {
        $f = $this->listFiles();
        if (in_array($filename, $f) === false) {
            return false;
        }

        return ssh2_scp_recv($this->connection, $this->serverPath .'/'. $filename, $localTarget);
    }

    /**
     * closes the connection
     * @return bool
     */
    public function disconnect()
    {
        if (empty($this->connection) === false) {
            ssh2_exec($this->connection, 'exit');
            ssh2_disconnect($this->connection);
            $this->connection = null;
            return true;
        }
        return false;
    }
}
