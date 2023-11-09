<?php

namespace App\Entity;

use App\Repository\OrganisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrganisateurRepository::class)]
class Organisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"enter un nom valide ")]
    private ?string $NomOrganisateur = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"enter un numero  ")]
    #[Assert\LessThan(100000000,message:"enter un numero valide  ")]
    #[Assert\GreaterThan(9999999,message:"enter un numero valide  ")]
    private ?int $NumTelOrganisateur = null;

    #[ORM\OneToOne(inversedBy: 'organisateur', cascade: ['persist', 'remove'])]
    private ?Adresse $Adresse = null;

    #[ORM\Column]
    #[Assert\LessThan(100)]
    #[Assert\NotBlank(message:"enter une pourcentage ")]
    private ?float $PourcentageRevenuOrganisateur = null;

    #[ORM\OneToMany(mappedBy: 'Organisateur', targetEntity: Evenement::class)]
    private Collection $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomOrganisateur(): ?string
    {
        return $this->NomOrganisateur;
    }

    public function setNomOrganisateur(string $NomOrganisateur): self
    {
        $this->NomOrganisateur = $NomOrganisateur;

        return $this;
    }

    public function getNumTelOrganisateur(): ?int
    {
        return $this->NumTelOrganisateur;
    }

    public function setNumTelOrganisateur(int $NumTelOrganisateur): self
    {
        $this->NumTelOrganisateur = $NumTelOrganisateur;

        return $this;
    }

    public function getAdresse(): ?Adresse
    {
        return $this->Adresse;
    }

    public function setAdresse(?Adresse $Adresse): self
    {
        $this->Adresse = $Adresse;

        return $this;
    }

    public function getPourcentageRevenuOrganisateur(): ?float
    {
        return $this->PourcentageRevenuOrganisateur;
    }

    public function setPourcentageRevenuOrganisateur(float $PourcentageRevenuOrganisateur): self
    {
        $this->PourcentageRevenuOrganisateur = $PourcentageRevenuOrganisateur;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements->add($evenement);
            $evenement->setOrganisateur($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getOrganisateur() === $this) {
                $evenement->setOrganisateur(null);
            }
        }

        return $this;
    }
}
