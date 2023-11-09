<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Point = null;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Therapist $Therapist = null;

    #[ORM\OneToOne(inversedBy: 'patient', cascade: ['persist', 'remove'])]
    private ?Utilisateur $IdUtilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoint(): ?int
    {
        return $this->Point;
    }

    public function setPoint(int $Point): self
    {
        $this->Point = $Point;

        return $this;
    }

    public function getTherapist(): ?Therapist
    {
        return $this->Therapist;
    }

    public function setTherapist(?Therapist $Therapist): self
    {
        $this->Therapist = $Therapist;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->IdUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $IdUtilisateur): self
    {
        $this->IdUtilisateur = $IdUtilisateur;

        return $this;
    }
}
