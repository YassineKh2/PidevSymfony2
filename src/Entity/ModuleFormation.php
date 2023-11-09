<?php

namespace App\Entity;

use App\Repository\ModuleFormationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: ModuleFormationRepository::class)]
class ModuleFormation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'moduleFormations')]
    private ?Formation $Formation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $NomModule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $PrerequisModule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $DureeModule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Length([
        'min'=>50,
        'max'=>255,
        'minMessage'=>'la description doit contenur au min 20 caracteres',
        'maxMessage'=>'la description doit contenur au max 255 caracteres'
    ])]
    private ?string $ContenuModule = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormation(): ?Formation
    {
        return $this->Formation;
    }

    public function setFormation(?Formation $Formation): self
    {
        $this->Formation = $Formation;

        return $this;
    }

    public function getNomModule(): ?string
    {
        return $this->NomModule;
    }

    public function setNomModule(string $NomModule): self
    {
        $this->NomModule = $NomModule;

        return $this;
    }

    public function getPrerequisModule(): ?string
    {
        return $this->PrerequisModule;
    }

    public function setPrerequisModule(string $PrerequisModule): self
    {
        $this->PrerequisModule = $PrerequisModule;

        return $this;
    }

    public function getDureeModule(): ?string
    {
        return $this->DureeModule;
    }

    public function setDureeModule(string $DureeModule): self
    {
        $this->DureeModule = $DureeModule;

        return $this;
    }

    public function getContenuModule(): ?string
    {
        return $this->ContenuModule;
    }

    public function setContenuModule(string $ContenuModule): self
    {
        $this->ContenuModule = $ContenuModule;

        return $this;
    }
}
