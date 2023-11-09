<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $NomUtilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $PrenomUtilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $PseudoUtilisateur = null;

    #[ORM\Column(length: 255)]
    private ?string $EmailUtilisateur = null;

    #[ORM\OneToOne(mappedBy: 'IdUtilisateur', cascade: ['persist', 'remove'])]
    private ?Therapist $therapist = null;

    #[ORM\OneToOne(mappedBy: 'IdUtilisateur', cascade: ['persist', 'remove'])]
    private ?Patient $patient = null;

    #[ORM\Column(length: 255)]
    private ?string $PasswordUtilisateur = null;

    #[ORM\ManyToMany(targetEntity: Evenement::class, inversedBy: 'utilisateurs')]
    private Collection $Evenements;

    #[ORM\OneToOne(mappedBy: 'client', cascade: ['persist', 'remove'])]
    private ?Pannier $pannier = null;

    #[ORM\OneToMany(mappedBy: 'Utilisateur', targetEntity: Publication::class)]
    private Collection $publications;

    #[ORM\OneToOne(mappedBy: 'Utilisateur', cascade: ['persist', 'remove'])]
    private ?ReactionPublication $reactionPublication = null;

    #[ORM\ManyToOne(inversedBy: 'Participant')]
    private ?Formation $formation = null;

    #[ORM\ManyToOne(inversedBy: 'Participant')]
    private ?Session $session = null;


    public function __construct()
    {
        $this->participantEvenements = new ArrayCollection();
        $this->Evenements = new ArrayCollection();
        $this->publications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->NomUtilisateur;
    }

    public function setNomUtilisateur(string $NomUtilisateur): self
    {
        $this->NomUtilisateur = $NomUtilisateur;

        return $this;
    }

    public function getPrenomUtilisateur(): ?string
    {
        return $this->PrenomUtilisateur;
    }

    public function setPrenomUtilisateur(string $PrenomUtilisateur): self
    {
        $this->PrenomUtilisateur = $PrenomUtilisateur;

        return $this;
    }

    public function getPseudoUtilisateur(): ?string
    {
        return $this->PseudoUtilisateur;
    }

    public function setPseudoUtilisateur(string $PseudoUtilisateur): self
    {
        $this->PseudoUtilisateur = $PseudoUtilisateur;

        return $this;
    }

    public function getEmailUtilisateur(): ?string
    {
        return $this->EmailUtilisateur;
    }

    public function setEmailUtilisateur(string $EmailUtilisateur): self
    {
        $this->EmailUtilisateur = $EmailUtilisateur;

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
            $this->therapist->setIdUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($therapist !== null && $therapist->getIdUtilisateur() !== $this) {
            $therapist->setIdUtilisateur($this);
        }

        $this->therapist = $therapist;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        // unset the owning side of the relation if necessary
        if ($patient === null && $this->patient !== null) {
            $this->patient->setIdUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($patient !== null && $patient->getIdUtilisateur() !== $this) {
            $patient->setIdUtilisateur($this);
        }

        $this->patient = $patient;

        return $this;
    }

    public function getPasswordUtilisateur(): ?string
    {
        return $this->PasswordUtilisateur;
    }

    public function setPasswordUtilisateur(string $PasswordUtilisateur): self
    {
        $this->PasswordUtilisateur = $PasswordUtilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Evenement>
     */
    public function getEvenements(): Collection
    {
        return $this->Evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->Evenements->contains($evenement)) {
            $this->Evenements->add($evenement);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        $this->Evenements->removeElement($evenement);

        return $this;
    }

    public function getPannier(): ?Pannier
    {
        return $this->pannier;
    }

    public function setPannier(?Pannier $pannier): self
    {
        // unset the owning side of the relation if necessary
        if ($pannier === null && $this->pannier !== null) {
            $this->pannier->setClient(null);
        }

        // set the owning side of the relation if necessary
        if ($pannier !== null && $pannier->getClient() !== $this) {
            $pannier->setClient($this);
        }

        $this->pannier = $pannier;

        return $this;
    }

    /**
     * @return Collection<int, Publication>
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications->add($publication);
            $publication->setUtilisateur($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUtilisateur() === $this) {
                $publication->setUtilisateur(null);
            }
        }

        return $this;
    }

    public function getReactionPublication(): ?ReactionPublication
    {
        return $this->reactionPublication;
    }

    public function setReactionPublication(?ReactionPublication $reactionPublication): self
    {
        // unset the owning side of the relation if necessary
        if ($reactionPublication === null && $this->reactionPublication !== null) {
            $this->reactionPublication->setUtilisateur(null);
        }

        // set the owning side of the relation if necessary
        if ($reactionPublication !== null && $reactionPublication->getUtilisateur() !== $this) {
            $reactionPublication->setUtilisateur($this);
        }

        $this->reactionPublication = $reactionPublication;

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getSession(): ?Session
    {
        return $this->session;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }


}
