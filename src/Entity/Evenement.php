<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"enter event name ")]
    private ?string $NomEvenement = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"enter date evenement")]
    #[Assert\GreaterThan('today')]
    private ?\DateTimeInterface $DateEvenement = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"entrer un nombre de participant max ")]
    #[Assert\GreaterThan(0)]
    private ?int $NombreParticipantEvenement = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"enter event price ")]
    #[Assert\GreaterThan(0)]
    private ?int $PrixEvenement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"enter Type Evenement ")]
    private ?string $TypeEvenement = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'Evenements')]
    private Collection $utilisateurs;

    #[ORM\ManyToOne(inversedBy: 'evenements')]
//    #[Assert\NotBlank(message:"enter event organizer ")]
    private ?Organisateur $Organisateur = null;

    #[ORM\OneToOne(inversedBy: 'evenement', cascade: ['persist', 'remove'])]
        private ?Adresse $Adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Imageevenement = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'evenements')]
    private Collection $EventLikes;

    #[ORM\Column(nullable: true)]
    private ?int $numberoflikes = 0;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"enter une description ")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\PositiveOrZero]
    private ?int $PlacesRestantes =0;

    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->EventLikes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomEvenement(): ?string
    {
        return $this->NomEvenement;
    }

    public function setNomEvenement(string $NomEvenement): self
    {
        $this->NomEvenement = $NomEvenement;

        return $this;
    }

    public function getDateEvenement(): ?\DateTimeInterface
    {
        return $this->DateEvenement;
    }

    public function setDateEvenement(\DateTimeInterface $DateEvenement): self
    {
        $this->DateEvenement = $DateEvenement;

        return $this;
    }

    public function getNombreParticipantEvenement(): ?int
    {
        return $this->NombreParticipantEvenement;
    }

    public function setNombreParticipantEvenement(int $NombreParticipantEvenement): self
    {
        $this->NombreParticipantEvenement = $NombreParticipantEvenement;

        return $this;
    }

    public function getPrixEvenement(): ?float
    {
        return $this->PrixEvenement;
    }

    public function setPrixEvenement(float $PrixEvenement): self
    {
        $this->PrixEvenement = $PrixEvenement;

        return $this;
    }

    public function getTypeEvenement(): ?string
    {
        return $this->TypeEvenement;
    }

    public function setTypeEvenement(string $TypeEvenement): self
    {
        $this->TypeEvenement = $TypeEvenement;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getUtilisateurs(): Collection
    {
        return $this->utilisateurs;
    }

    public function addUtilisateur(Utilisateur $utilisateur): self
    {
        if (!$this->utilisateurs->contains($utilisateur)) {
            $this->utilisateurs->add($utilisateur);
            $utilisateur->addEvenement($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeEvenement($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?Organisateur
    {
        return $this->Organisateur;
    }

    public function setOrganisateur(?Organisateur $Organisateur): self
    {
        $this->Organisateur = $Organisateur;

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

    public function getImageevenement(): ?string
    {
        return $this->Imageevenement;
    }

    public function setImageevenement(?string $Imageevenement): self
    {
        $this->Imageevenement = $Imageevenement;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getEventLikes(): Collection
    {
        return $this->EventLikes;
    }

    public function addEventLike(Utilisateur $eventLike): self
    {
        if (!$this->EventLikes->contains($eventLike)) {
            $this->EventLikes->add($eventLike);
        }

        return $this;
    }

    public function removeEventLike(Utilisateur $eventLike): self
    {
        $this->EventLikes->removeElement($eventLike);

        return $this;
    }

    public function getNumberoflikes(): ?int
    {
        return $this->numberoflikes;
    }

    public function setNumberoflikes(?int $numberoflikes): self
    {
        $this->numberoflikes = $numberoflikes;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPlacesRestantes(): ?int
    {
        return $this->PlacesRestantes;
    }

    public function setPlacesRestantes(int $PlacesRestantes): self
    {
        $this->PlacesRestantes = $PlacesRestantes;

        return $this;
    }
}
