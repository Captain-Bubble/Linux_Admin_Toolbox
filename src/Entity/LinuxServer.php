<?php

namespace App\Entity;

use App\Repository\LinuxServerRepository;
use Doctrine\ORM\Mapping as ORM;
use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;

/**
 * @ORM\Entity(repositoryClass=LinuxServerRepository::class)
 * @ORM\Table(indexes={@ORM\Index(columns={"id"})})
 */
class LinuxServer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $serverName = "";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $host;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username = "";

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Encrypted
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $privateKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $publicKey;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passphrase;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $requireSudo;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $requirePasswordAfterSudo;


    public function getId() : ?int
    {
        return $this->id;
    }

    public function getHost() : ?string
    {
        return $this->host;
    }

    public function setHost(string $host) : self
    {
        $this->host = $host;

        return $this;
    }

    public function getUsername() : ?string
    {
        return $this->username;
    }

    public function setUsername(string $username) : self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword() : ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password) : self
    {
        $this->password = $password;

        return $this;
    }

    public function getPrivateKey() : ?string
    {
        return $this->privateKey;
    }

    public function setPrivateKey(?string $privateKey) : self
    {
        $this->privateKey = $privateKey;

        return $this;
    }

    public function getRequireSudo() : ?bool
    {
        return $this->requireSudo;
    }

    public function setRequireSudo(bool $requireSudo) : self
    {
        $this->requireSudo = $requireSudo;

        return $this;
    }

    public function getRequirePasswordAfterSudo() : ?bool
    {
        return $this->requirePasswordAfterSudo;
    }

    public function setRequirePasswordAfterSudo(bool $requirePasswordAfterSudo) : self
    {
        $this->requirePasswordAfterSudo = $requirePasswordAfterSudo;

        return $this;
    }

    public function getPublicKey() : ?string
    {
        return $this->publicKey;
    }

    public function setPublicKey(string $publicKey) : self
    {
        $this->publicKey = $publicKey;

        return $this;
    }

    public function getPassphrase() : ?string
    {
        return $this->passphrase;
    }

    public function setPassphrase(?string $passphrase) : self
    {
        $this->passphrase = $passphrase;

        return $this;
    }

    public function getServerName() : ?string
    {
        return $this->serverName;
    }

    public function setServerName(string $serverName) : self
    {
        $this->serverName = $serverName;

        return $this;
    }
}
