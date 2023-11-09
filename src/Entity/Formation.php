<?php

namespace App\Entity;

use App\Repository\FormationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FormationRepository::class)]
class Formation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['Formation'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Groups(['Formation'])]
    private ?string $NomFormation = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Positive(message: "il faut que cette valeur contient que des nombres")]
    #[Groups(['Formation'])]
    private ?int $NiveauFormation = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: Utilisateur::class)]
    private Collection $Participant;

    #[ORM\OneToMany(mappedBy: 'Formation', targetEntity: ModuleFormation::class)]
    #[Groups(['Formation'])]
    private Collection $moduleFormations;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[Groups(['Formation'])]
    private ?Formateur $Formateur = null;

    #[ORM\Column(length: 255)]
    #[Groups(['Formation'])]
    private ?string $ImageFormation = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Length([
        'min'=>50,
        'max'=>255,
        'minMessage'=>'la description doit contenur au min 20 caracteres',
        'maxMessage'=>'la description doit contenur au max 255 caracteres'
    ])]
    #[Groups(['Formation'])]
    private ?string $DescriptionFormation = null;

    #[ORM\OneToMany(mappedBy: 'formation', targetEntity: User::class)]
    private Collection $participantsUser;



    public function __construct()
    {
        $this->Participant = new ArrayCollection();
        $this->moduleFormations = new ArrayCollection();
        $this->participantsUser = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFormation(): ?string
    {
        return $this->NomFormation;
    }

    public function setNomFormation(string $NomFormation): self
    {
        $this->NomFormation = $NomFormation;

        return $this;
    }

    public function getNiveauFormation(): ?int
    {
        return $this->NiveauFormation;
    }

    public function setNiveauFormation(int $NiveauFormation): self
    {
        $this->NiveauFormation = $NiveauFormation;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getParticipant(): Collection
    {
        return $this->Participant;
    }

    public function addParticipant(Utilisateur $participant): self
    {
        if (!$this->Participant->contains($participant)) {
            $this->Participant->add($participant);
            $participant->setFormation($this);
        }

        return $this;
    }

    public function removeParticipant(Utilisateur $participant): self
    {
        if ($this->Participant->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getFormation() === $this) {
                $participant->setFormation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModuleFormation>
     */
    public function getModuleFormations(): Collection
    {
        return $this->moduleFormations;
    }

    public function addModuleFormation(ModuleFormation $moduleFormation): self
    {
        if (!$this->moduleFormations->contains($moduleFormation)) {
            $this->moduleFormations->add($moduleFormation);
            $moduleFormation->setFormation($this);
        }

        return $this;
    }

    public function removeModuleFormation(ModuleFormation $moduleFormation): self
    {
        if ($this->moduleFormations->removeElement($moduleFormation)) {
            // set the owning side to null (unless already changed)
            if ($moduleFormation->getFormation() === $this) {
                $moduleFormation->setFormation(null);
            }
        }

        return $this;
    }

    public function getFormateur(): ?Formateur
    {
        return $this->Formateur;
    }

    public function setFormateur(?Formateur $Formateur): self
    {
        $this->Formateur = $Formateur;

        return $this;
    }

    public function getImageFormation(): ?string
    {
        return $this->ImageFormation;
    }

    public function setImageFormation(string $ImageFormation): self
    {
        $this->ImageFormation = $ImageFormation;

        return $this;
    }

    public function getDescriptionFormation(): ?string
    {
        return $this->DescriptionFormation;
    }

    public function setDescriptionFormation(string $DescriptionFormation): self
    {
        $this->DescriptionFormation = $DescriptionFormation;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipantsUser(): Collection
    {
        return $this->participantsUser;
    }

    public function addParticipantsUser(User $participantsUser): self
    {
        if (!$this->participantsUser->contains($participantsUser)) {
            $this->participantsUser->add($participantsUser);
            $participantsUser->setFormation($this);
        }

        return $this;
    }

    public function removeParticipantsUser(User $participantsUser): self
    {
        if ($this->participantsUser->removeElement($participantsUser)) {
            // set the owning side to null (unless already changed)
            if ($participantsUser->getFormation() === $this) {
                $participantsUser->setFormation(null);
            }
        }

        return $this;
    }

}
