<?php

namespace App\Entity;

use App\Repository\DespenseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DespenseRepository::class)]
class Despense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $LibelleDespense = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Positive(message: "il faut que ce nombre est positive")]
    private ?float $MontantDespense = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Positive(message: "il faut que ce nombre est positive")]
    #[Assert\LessThanOrEqual(100 , message: "Ce nombre doit etre inferieur ou egale a 100")]
    private ?float $ReductionDespense = null;

    #[ORM\OneToOne(mappedBy: 'Despense', targetEntity: Session::class, cascade: ['persist', 'remove'], fetch: 'EAGER', orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Session $Session = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleDespense(): ?string
    {
        return $this->LibelleDespense;
    }

    public function setLibelleDespense(string $LibelleDespense): self
    {
        $this->LibelleDespense = $LibelleDespense;

        return $this;
    }

    public function getMontantDespense(): ?float
    {
        return $this->MontantDespense;
    }

    public function setMontantDespense(float $MontantDespense): self
    {
        $this->MontantDespense = $MontantDespense;

        return $this;
    }

    public function getReductionDespense(): ?float
    {
        return $this->ReductionDespense;
    }

    public function setReductionDespense(float $ReductionDespense): self
    {
        $this->ReductionDespense = $ReductionDespense;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->Session;
    }

    public function setSession(?Session $Session): self
    {
        $this->Session = $Session;

        return $this;
    }
}
