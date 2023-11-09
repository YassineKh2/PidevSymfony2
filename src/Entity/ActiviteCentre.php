<?php

namespace App\Entity;

use App\Repository\ActiviteCentreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;




#[ORM\Entity(repositoryClass: ActiviteCentreRepository::class)]
class ActiviteCentre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activiteCentres')]
    private ?PlanningCentre $Planning = null;


    #[Assert\NotBlank(message:"Saisir le jour SVP")]
    #[ORM\Column(length: 255)]
    private ?string $JourActivite = null;




    #[Assert\NotBlank(message:"Saisir le nom SVP")]
    #[Assert\Length([
        'min' => 5,
        'max' => 50,

        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères',
        'maxMessage' => 'Le nom  doit comporter au moins {{ limit }} caractères',

    ])]
    #[ORM\Column(length: 255)]
    private ?string $NomActivite = null;



    #[Assert\NotBlank(message:"Saisir le contenu SVP")]
    #[Assert\Length([
        'min' => 5,
        'max' => 50,
        'minMessage' => 'Le contenu doit comporter au moins {{ limit }} caractères',
        'maxMessage' => 'Le contenu  doit comporter au moins {{ limit }} caractères',
    ])]
    #[ORM\Column(length: 255)]
    private ?string $ContenuActivite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $HeureDebutActivite = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $HeureFinActivite = null;

    #[ORM\Column]
    private ?int $NombreParticipantActiviteMax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanning(): ?PlanningCentre
    {
        return $this->Planning;
    }

    public function setPlanning(?PlanningCentre $Planning): self
    {
        $this->Planning = $Planning;

        return $this;
    }

    public function getJourActivite(): ?string
    {
        return $this->JourActivite;
    }

    public function setJourActivite(string $JourActivite): self
    {
        $this->JourActivite = $JourActivite;

        return $this;
    }

    public function getNomActivite(): ?string
    {
        return $this->NomActivite;
    }

    public function setNomActivite(string $NomActivite): self
    {
        $this->NomActivite = $NomActivite;

        return $this;
    }

    public function getContenuActivite(): ?string
    {
        return $this->ContenuActivite;
    }

    public function setContenuActivite(string $ContenuActivite): self
    {
        $this->ContenuActivite = $ContenuActivite;

        return $this;
    }

    public function getHeureDebutActivite(): ?\DateTimeInterface
    {
        return $this->HeureDebutActivite;
    }

    public function setHeureDebutActivite(\DateTimeInterface $HeureDebutActivite): self
    {
        $this->HeureDebutActivite = $HeureDebutActivite;

        return $this;
    }

    public function getHeureFinActivite(): ?\DateTimeInterface
    {
        return $this->HeureFinActivite;
    }

    public function setHeureFinActivite(\DateTimeInterface $HeureFinActivite): self
    {
        $this->HeureFinActivite = $HeureFinActivite;

        return $this;
    }

    public function getNombreParticipantActiviteMax(): ?int
    {
        return $this->NombreParticipantActiviteMax;
    }

    public function setNombreParticipantActiviteMax(int $NombreParticipantActiviteMax): self
    {
        $this->NombreParticipantActiviteMax = $NombreParticipantActiviteMax;

        return $this;
    }
}
