<?php

namespace App\Entity;

use App\Repository\PlanningCentreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PlanningCentreRepository::class)]
class PlanningCentre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'planningCentres')]
    private ?Centre $Centre = null;
    #[Groups("plannings")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today")]
    private ?\DateTimeInterface $DateDebutPlanning = null;
    #[Groups("plannings")]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Range(min: '+3 day',max: "+3 year")]
    private ?\DateTimeInterface $DateFinPlanning = null;

    #[ORM\OneToMany(mappedBy: 'Planning', targetEntity: ActiviteCentre::class)]
    private Collection $activiteCentres;

    #[Groups("plannings")]
    #[Assert\NotBlank(message:"Saisir le titre SVP")]
    #[Assert\Length([
        'min' => 5,
        'max' => 50,
        'minMessage' => 'Le Titre doit comporter au moins {{ limit }} caractères',
        'maxMessage' => 'Le Titre  doit comporter au moins {{ limit }} caractères',
    ])]
    #[ORM\Column(length: 50)]
    private ?string $Titre = null;

    #[Groups("plannings")]
    #[Assert\NotBlank(message:"Saisir la  descrition SVP")]
    #[Assert\Length([
        'min' => 20,
        'max' => 600,
        'minMessage' => 'La description doit comporter au moins {{ limit }} caractères',
        'maxMessage' => 'La description  doit comporter au moins {{ limit }} caractères',
    ])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column(length: 255)]
    private ?string $Image = null;

    public function __construct()
    {
        $this->activiteCentres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public ?String $titre="";
    public function setCentreNom(String $titre): self
    {
        $this->titre = $titre;

        return $this;
    }
    public ?String $local="";
    public function setCentreLocale(String $local): self
    {
        $this->local = $local;

        return $this;
    }
    public function getCentre(): ?Centre
    {
        return $this->Centre;
    }

    public function setCentre(?Centre $Centre): self
    {
        $this->Centre = $Centre;

        return $this;
    }

    public function getDateDebutPlanning(): ?\DateTimeInterface
    {
        return $this->DateDebutPlanning;
    }

    public function setDateDebutPlanning(\DateTimeInterface $DateDebutPlanning): self
    {
        $this->DateDebutPlanning = $DateDebutPlanning;

        return $this;
    }

    public function getDateFinPlanning(): ?\DateTimeInterface
    {
        return $this->DateFinPlanning;
    }

    public function setDateFinPlanning(\DateTimeInterface $DateFinPlanning): self
    {
        $this->DateFinPlanning = $DateFinPlanning;

        return $this;
    }
    public function __toString():string{
        return $this->Titre;}

    /**
     * @return Collection<int, ActiviteCentre>
     */
    public function getActiviteCentres(): Collection
    {
        return $this->activiteCentres;
    }

    public function addActiviteCentre(ActiviteCentre $activiteCentre): self
    {
        if (!$this->activiteCentres->contains($activiteCentre)) {
            $this->activiteCentres->add($activiteCentre);
            $activiteCentre->setPlanning($this);
        }

        return $this;
    }

    public function removeActiviteCentre(ActiviteCentre $activiteCentre): self
    {
        if ($this->activiteCentres->removeElement($activiteCentre)) {
            // set the owning side to null (unless already changed)
            if ($activiteCentre->getPlanning() === $this) {
                $activiteCentre->setPlanning(null);
            }
        }

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->Titre;
    }

    public function setTitre(string $Titre): self
    {
        $this->Titre = $Titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->Image;
    }

    public function setImage(string $Image): self
    {
        $this->Image = $Image;

        return $this;
    }

}