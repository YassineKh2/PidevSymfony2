<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomAdmin = null;

    #[ORM\Column(length: 255)]
    private ?string $PasswordAdmin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomAdmin(): ?string
    {
        return $this->NomAdmin;
    }

    public function setNomAdmin(string $NomAdmin): self
    {
        $this->NomAdmin = $NomAdmin;

        return $this;
    }

    public function getPasswordAdmin(): ?string
    {
        return $this->PasswordAdmin;
    }

    public function setPasswordAdmin(string $PasswordAdmin): self
    {
        $this->PasswordAdmin = $PasswordAdmin;

        return $this;
    }
}
