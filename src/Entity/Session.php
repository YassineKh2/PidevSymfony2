<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: SessionRepository::class)]

class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today")]
    private ?\DateTimeInterface $DateDebutSession = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\GreaterThanOrEqual("today")]
    private ?\DateTimeInterface $DateFinSession = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Positive(message: "il faut que ce nombre est positive")]
    private ?int $NombreParticipantSession = null;

    #[ORM\Column(length: 255)]
    private ?string $Difficulte = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    #[Assert\Length([
        'min'=>50,
        'max'=>255,
        'minMessage'=>'la description doit contenur au min 20 caracteres',
        'maxMessage'=>'la description doit contenur au max 255 caracteres'
    ])]
    private ?string $DescriptionSession = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "if fault que ce champ est remplie")]
    private ?string $NomSession = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Utilisateur::class)]
    private Collection $Participan;

    #[ORM\Column(length: 255)]
    private ?string $ImageSession = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]

    private ?Despense $Despense = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: User::class)]
    private Collection $ParticipantSession;

    public function __construct()
    {
        $this->Participan = new ArrayCollection();
        $this->ParticipantSession = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutSession(): ?\DateTimeInterface
    {
        return $this->DateDebutSession;
    }

    public function setDateDebutSession(\DateTimeInterface $DateDebutSession): self
    {
        $this->DateDebutSession = $DateDebutSession;

        return $this;
    }

    public function getDateFinSession(): ?\DateTimeInterface
    {
        return $this->DateFinSession;
    }

    public function setDateFinSession(\DateTimeInterface $DateFinSession): self
    {
        $this->DateFinSession = $DateFinSession;

        return $this;
    }

    public function getNombreParticipantSession(): ?int
    {
        return $this->NombreParticipantSession;
    }

    public function setNombreParticipantSession(int $NombreParticipantSession): self
    {
        $this->NombreParticipantSession = $NombreParticipantSession;

        return $this;
    }

    public function getDifficulte(): ?string
    {
        return $this->Difficulte;
    }

    public function setDifficulte(string $Difficulte): self
    {
        $this->Difficulte = $Difficulte;

        return $this;
    }

    public function getDescriptionSession(): ?string
    {
        return $this->DescriptionSession;
    }

    public function setDescriptionSession(string $DescriptionSession): self
    {
        $this->DescriptionSession = $DescriptionSession;

        return $this;
    }

    public function getNomSession(): ?string
    {
        return $this->NomSession;
    }

    public function setNomSession(string $NomSession): self
    {
        $this->NomSession = $NomSession;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getParticipan(): Collection
    {
        return $this->Participan;
    }

    public function addParticipan(Utilisateur $participan): self
    {
        if (!$this->Participan->contains($participan)) {
            $this->Participan->add($participan);
            $participan->setSession($this);
        }

        return $this;
    }

    public function removeParticipan(Utilisateur $participan): self
    {
        if ($this->Participan->removeElement($participan)) {
            // set the owning side to null (unless already changed)
            if ($participan->getSession() === $this) {
                $participan->setSession(null);
            }
        }

        return $this;
    }

    public function getImageSession(): ?string
    {
        return $this->ImageSession;
    }

    public function setImageSession(string $ImageSession): self
    {
        $this->ImageSession = $ImageSession;

        return $this;
    }

    public function getDespense(): ?Despense
    {
        return $this->Despense;
    }

    public function setDespense(?Despense $Despense): self
    {
        $this->Despense = $Despense;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getParticipantSession(): Collection
    {
        return $this->ParticipantSession;
    }

    public function addParticipantSession(User $participantSession): self
    {
        if (!$this->ParticipantSession->contains($participantSession)) {
            $this->ParticipantSession->add($participantSession);
            $participantSession->setSession($this);
        }

        return $this;
    }

    public function removeParticipantSession(User $participantSession): self
    {
        if ($this->ParticipantSession->removeElement($participantSession)) {
            // set the owning side to null (unless already changed)
            if ($participantSession->getSession() === $this) {
                $participantSession->setSession(null);
            }
        }

        return $this;
    }
}
