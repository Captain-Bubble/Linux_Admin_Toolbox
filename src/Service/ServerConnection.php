<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
use App\Entity\LinuxServer;
use Exception;
use Symfony\Component\HttpFoundation\Session\Session;

class ServerConnection
{

    private LinuxServer|null $user = null;
    private $connection = null;
    private $output = '';
    private $serverPath = '/';
    private $em = null;

    public function __construct(EntityManager $em, Session $session)
    {
        $this->em = $em;
        $currAcc = $session->get('activeServer', 0);
        if ($currAcc != 0) {
            $this->setUser($currAcc);
        }
    }

    public function setUser(int $id) : void
    {
        $la = $this->em->getRepository(LinuxServer::class);
        $this->user = $la->find($id);
    }


    /**
     * tries if connection is working
     * @return bool
     */
    public function tryConnect() : bool
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
     * @return ServerConnection $this
     * @throws Exception
     */
    public function connect() : ServerConnection
    {
        $this->connection = ssh2_connect($this->user->getHost());
        if ($this->connection === false) {
            throw new Exception('error_connection', 1);
        }

        $authSuccess = false;
        if (empty(trim($this->user->getPrivateKey())) === false) {
            $authSuccess = ssh2_auth_pubkey_file(
                $this->connection,
                $this->user->getUsername(),
                $this->user->getPublicKey(),
                $this->user->getPrivateKey(),
                $this->user->getPassphrase()
            );
        }

        if (!$authSuccess && empty($this->user->getPassword()) === false) {
            $authSuccess = ssh2_auth_password(
                $this->connection,
                $this->user->getUsername(),
                $this->user->getPassword()
            );

            if ($this->user->getRequireSudo()) {
                $cmd = 'echo "'. $this->user->getPassword() .'" | sudo -S "echo \'\'"';
                ssh2_exec($this->connection, $cmd);
            }
        }

        if ($authSuccess) {
            $stream = ssh2_exec($this->connection, 'realpath .');
            stream_set_blocking($stream, true);
            $stream = stream_get_contents($stream);
            $this->serverPath = $stream;
        } else {
            throw new Exception('error_auth', 2);
        }
        return $this;
    }

    /**
     * executes commands
     * the result will be stored in output ( use getOutput() )
     * @param $cmd
     * @return ServerConnection $this
     * @throws Exception
     */
    public function exec($cmd) : ServerConnection
    {
        if (empty($this->connection) === true) {
            throw new Exception('error_no_connection', 3);
        }
        $stream = ssh2_exec($this->connection, 'cd '. $this->serverPath .'; '. $cmd);
//        $stream = ssh2_fetch_stream($exec, SSH2_STREAM_STDIO);
//        stream_set_blocking($stream, true);
        dump($this->serverPath);
        dump($cmd);
        dump($stream);
        $this->output = stream_get_contents($stream);
        dump($this->output);
        return $this;
    }

    /**
     * returns the output of the previous command ( exec($cmd) )
     * @return string
     */
    public function getOutput() : string
    {
        return $this->output;
    }

    /**
     * changes the folder on the server
     * accepts relative or absolute path to folder
     * does not check if path not exist!
     * @param $folder
     * @return ServerConnection $this
     */
    public function gotoServerFolder($folder) : ServerConnection
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
        $this->serverPath = str_replace('//', '/', $this->serverPath);

        return $this;
    }

    /**
     * lists all folders in current path
     * @return array
     * @throws Exception
     */
    public function listFolders() : array
    {
        $this->exec('cd "'. $this->serverPath .'";ls -d */');
        $res = explode(PHP_EOL, $this->output);
        foreach ($res as $v) {
            if (empty($v) === false) {
                $ret[] = str_replace('/', '', $v);
            }
        }
        return $ret;
    }

    /**
     * lists all files in current path
     * @return array
     * @throws Exception
     */
    public function listFiles() : array
    {
        $ret = array();
        $this->exec('cd "'. $this->serverPath .'";ls -p | grep -v /');
        $res = explode(PHP_EOL, $this->output);
        foreach ($res as $v) {
            if (empty($v) === false) {
                $ret[] = $v;
            }
        }
        return $ret;
    }

    /**
     * uploads file to server
     * requires absolute path for uploaded file
     * if $targetFile name is not set, the current name will be used
     * @param string $file Absolute path to file
     * @param null   $targetFile new filename
     * @return bool
     * @throws Exception
     */
    public function uploadToServer(string $file, $targetFile = null) : bool
    {
        if (file_exists($file) === false) {
            return false;
        }
        if (!str_contains($file, '/')) {
            return false;
        }
        if (empty($targetFile) === true) {
            $ex = explode('/', $file);
            $targetFile = end($ex);
        }

        ssh2_scp_send($this->connection, $file, $this->serverPath .'/'. $targetFile, 0666);

        $tPath = $this->serverPath;
        $this->disconnect();
        $this->connect();
        $this->gotoServerFolder($tPath);

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
    public function disconnect() : bool
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
