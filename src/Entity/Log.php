<?php

namespace App\Entity;

use App\Repository\LogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LogRepository::class)
 */
class Log
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=10000)
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $token_user_emisor;

    /**
     * @return mixed
     */
    public function getTokenUserEmisor()
    {
        return $this->token_user_emisor;
    }

    /**
     * @param mixed $token_user_emisor
     */
    public function setTokenUserEmisor($token_user_emisor): void
    {
        $this->token_user_emisor = $token_user_emisor;
    }

    /**
     * @return mixed
     */
    public function getUserReceptor()
    {
        return $this->user_receptor;
    }

    /**
     * @param mixed $user_receptor
     */
    public function setUserReceptor($user_receptor): void
    {
        $this->user_receptor = $user_receptor;
    }

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $user_receptor;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

}
