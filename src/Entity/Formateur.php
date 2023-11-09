<?php

namespace App\Entity;

use App\Repository\FormateurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormateurRepository::class)]
class Formateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $NomFormateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $PrenomFormateur = null;

    #[ORM\Column(length: 255)]

    private ?string $SexeFormateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Email(message: "il faut taper un email valide")]
    private ?string $EmailFormateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Regex("/^\d{8}$/",message: "Ce champ doit avoir 8 nombres ")]
    private ?int $NumTelFormateur = null;

    #[ORM\Column(length: 255)]
    private ?string $ImageFormateur = null;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormateur(): ?string
    {
        return $this->NomFormateur;
    }

    public function setNomFormateur(string $NomFormateur): self
    {
        $this->NomFormateur = $NomFormateur;

        return $this;
    }

    public function getPrenomFormateur(): ?string
    {
        return $this->PrenomFormateur;
    }

    public function setPrenomFormateur(string $PrenomFormateur): self
    {
        $this->PrenomFormateur = $PrenomFormateur;

        return $this;
    }

    public function getSexeFormateur(): ?string
    {
        return $this->SexeFormateur;
    }

    public function setSexeFormateur(string $SexeFormateur): self
    {
        $this->SexeFormateur = $SexeFormateur;

        return $this;
    }

    public function getEmailFormateur(): ?string
    {
        return $this->EmailFormateur;
    }

    public function setEmailFormateur(string $EmailFormateur): self
    {
        $this->EmailFormateur = $EmailFormateur;

        return $this;
    }

    public function getNumTelFormateur(): ?int
    {
        return $this->NumTelFormateur;
    }

    public function setNumTelFormateur(int $NumTelFormateur): self
    {
        $this->NumTelFormateur = $NumTelFormateur;

        return $this;
    }

    public function getImageFormateur(): ?string
    {
        return $this->ImageFormateur;
    }

    public function setImageFormateur(string $ImageFormateur): self
    {
        $this->ImageFormateur = $ImageFormateur;

        return $this;
    }


}
