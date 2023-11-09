<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
//    #[Assert\NotBlank]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"enter Nom Rue")]
    private ?string $NomRue = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"enter Numero Rue")]
    #[Assert\GreaterThan(0)]
    private ?int $NumRue = null;

    #[ORM\Column]
    #[Assert\LessThan(10000)]
    #[Assert\NotBlank(message:"enter Code Postal")]
    private ?int $CodePostal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"enter Gouvernorat")]
    private ?string $Gouvernorat = null;

    #[ORM\OneToOne(mappedBy: 'Adresse', cascade: ['persist', 'remove'])]
    private ?Therapist $therapist = null;

    #[ORM\OneToOne(mappedBy: 'Adresse', cascade: ['persist', 'remove'])]
    private ?Organisateur $organisateur = null;

    #[ORM\OneToOne(mappedBy: 'Adresse', cascade: ['persist', 'remove'])]
    private ?Centre $centre = null;

    #[ORM\OneToOne(mappedBy: 'Adresse', cascade: ['persist', 'remove'])]
    private ?Evenement $evenement = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"enter adresse map")]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"enter adresse map")]
    private ?float $longitude = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomRue(): ?string
    {
        return $this->NomRue;
    }

    public function setNomRue(string $NomRue): self
    {
        $this->NomRue = $NomRue;

        return $this;
    }

    public function getNumRue(): ?int
    {
        return $this->NumRue;
    }

    public function setNumRue(int $NumRue): self
    {
        $this->NumRue = $NumRue;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->CodePostal;
    }

    public function setCodePostal(int $CodePostal): self
    {
        $this->CodePostal = $CodePostal;

        return $this;
    }

    public function getGouvernorat(): ?string
    {
        return $this->Gouvernorat;
    }

    public function setGouvernorat(string $Gouvernorat): self
    {
        $this->Gouvernorat = $Gouvernorat;

        return $this;
    }

    public function getTherapist(): ?Therapist
    {
        return $this->therapist;
    }

    public function setTherapist(?Therapist $therapist): self
    {
        // unset the owning side of the relation if necessary
        if ($therapist === null && $this->therapist !== null) {
            $this->therapist->setAdresse(null);
        }

        // set the owning side of the relation if necessary
        if ($therapist !== null && $therapist->getAdresse() !== $this) {
            $therapist->setAdresse($this);
        }

        $this->therapist = $therapist;

        return $this;
    }

    public function getOrganisateur(): ?Organisateur
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?Organisateur $organisateur): self
    {
        // unset the owning side of the relation if necessary
        if ($organisateur === null && $this->organisateur !== null) {
            $this->organisateur->setAdresse(null);
        }

        // set the owning side of the relation if necessary
        if ($organisateur !== null && $organisateur->getAdresse() !== $this) {
            $organisateur->setAdresse($this);
        }

        $this->organisateur = $organisateur;

        return $this;
    }

    public function getCentre(): ?Centre
    {
        return $this->centre;
    }

    public function setCentre(?Centre $centre): self
    {
        // unset the owning side of the relation if necessary
        if ($centre === null && $this->centre !== null) {
            $this->centre->setAdresse(null);
        }

        // set the owning side of the relation if necessary
        if ($centre !== null && $centre->getAdresse() !== $this) {
            $centre->setAdresse($this);
        }

        $this->centre = $centre;

        return $this;
    }

    public function getEvenement(): ?Evenement
    {
        return $this->evenement;
    }

    public function setEvenement(?Evenement $evenement): self
    {
        // unset the owning side of the relation if necessary
        if ($evenement === null && $this->evenement !== null) {
            $this->evenement->setAdresse(null);
        }

        // set the owning side of the relation if necessary
        if ($evenement !== null && $evenement->getAdresse() !== $this) {
            $evenement->setAdresse($this);
        }

        $this->evenement = $evenement;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
