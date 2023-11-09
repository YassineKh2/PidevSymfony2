<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\CentreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: CentreRepository::class)]
class Centre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("centres")]
    private ?int $id = null;


    #[Groups("centres")]
    #[ORM\Column(length: 255)]
    private ?string $NomCentre = null;

    #[Groups("centres")]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Regex("/^\d{8}$/",message: "Ce champ doit avoir 8 nombres ")]
    #[ORM\Column]
    private ?string $CapaciteCentre = null;




    #[ORM\Column]
    private ?int $NombreBlocCentre = null;


    #[Groups("centres")]
    #[ORM\OneToOne(inversedBy: 'centre', cascade: ['persist', 'remove'])]
    private ?Adresse $Adresse = null;

    #[ORM\OneToMany(mappedBy: 'Centre', targetEntity: PlanningCentre::class)]
    private Collection $planningCentres;
    #[Groups("centres")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $localisation = null;
    #[Groups("centres")]
    #[ORM\Column(length: 255)]
    private ?string $img = null;

    public function __construct()
    {
        $this->planningCentres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCentre(): ?string
    {
        return $this->NomCentre;
    }

    public function setNomCentre(string $NomCentre): self
    {
        $this->NomCentre = $NomCentre;

        return $this;
    }

    public function getCapaciteCentre(): ?string
    {
        return $this->CapaciteCentre;
    }

    public function setCapaciteCentre(string $CapaciteCentre): self
    {
        $this->CapaciteCentre = $CapaciteCentre;

        return $this;
    }

    public function getNombreBlocCentre(): ?int
    {
        return $this->NombreBlocCentre;
    }

    public function setNombreBlocCentre(int $NombreBlocCentre): self
    {
        $this->NombreBlocCentre = $NombreBlocCentre;

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
    public function __toString():string{
        return $this->NomCentre;}
    /**
     * @return Collection<int, PlanningCentre>
     */
    public function getPlanningCentres(): Collection
    {
        return $this->planningCentres;
    }

    public function addPlanningCentre(PlanningCentre $planningCentre): self
    {
        if (!$this->planningCentres->contains($planningCentre)) {
            $this->planningCentres->add($planningCentre);
            $planningCentre->setCentre($this);
        }

        return $this;
    }

    public function removePlanningCentre(PlanningCentre $planningCentre): self
    {
        if ($this->planningCentres->removeElement($planningCentre)) {
            // set the owning side to null (unless already changed)
            if ($planningCentre->getCentre() === $this) {
                $planningCentre->setCentre(null);
            }
        }

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(?string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }


}
