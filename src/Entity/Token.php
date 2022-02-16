<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=TokenRepository::class)
 */
class Token
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=300)
     */
    private $TokenID;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $InstanceID;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTokenID(): ?string
    {
        return $this->TokenID;
    }

    public function setTokenID(string $TokenID): self
    {
        $this->TokenID = $TokenID;

        return $this;
    }

    public function getInstanceID(): ?string
    {
        return $this->InstanceID;
    }

    public function setInstanceID(string $InstanceID): self
    {
        $this->InstanceID = $InstanceID;

        return $this;
    }
}
