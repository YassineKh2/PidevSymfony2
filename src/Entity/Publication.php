<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PublicationRepository::class)]
class Publication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("publications")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    private ?Utilisateur $Utilisateur = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("publications")]
    private ?\DateTimeInterface $DatePublication = null;
    
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:" ce champs est requis")]
    #[Groups("publications")]
    private ?string $ContenuPublication = null;

    #[ORM\OneToMany(mappedBy: 'Publication', targetEntity: CommantairePublication::class)]
    private Collection $commantairePublications;

    #[ORM\OneToMany(mappedBy: 'Publication', targetEntity: ReactionPublication::class)]
    private Collection $reactionPublications;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: CommantairePublication::class)]
    private Collection $commentaires;

    #[ORM\OneToMany(mappedBy: 'publication', targetEntity: Utilisateur::class)]
    private Collection $user;

   // #[ORM\OneToMany(mappedBy: 'publication', targetEntity: ReactionPublication::class)]
   // private Collection $pubReaction;

    #[ORM\Column(length: 255)]
    #[Groups("publications")]
    private ?string $ImageForum = null;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $isApproved=false;

    #[ORM\ManyToOne(inversedBy: 'publications')]
    private ?User $User = null;

    public function __construct()
    {
        $this->commantairePublications = new ArrayCollection();
        $this->reactionPublications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->user = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->Utilisateur;
    }

    public function setUtilisateur(?Utilisateur $Utilisateur): self
    {
        $this->Utilisateur = $Utilisateur;

        return $this;
    }

    public function getDatePublication(): ?\DateTimeInterface
    {
        return $this->DatePublication;
    }

    public function setDatePublication(\DateTimeInterface $DatePublication): self
    {
        $this->DatePublication = $DatePublication;

        return $this;
    }

    public function getContenuPublication(): ?string
    {
        return $this->ContenuPublication;
    }

    public function setContenuPublication(string $ContenuPublication): self
    {
        $this->ContenuPublication = $ContenuPublication;

        return $this;
    }

    /**
     * @return Collection<int, CommantairePublication>
     */
    public function getCommantairePublications(): Collection
    {
        return $this->commantairePublications;
    }

    public function addCommantairePublication(CommantairePublication $commantairePublication): self
    {
        if (!$this->commantairePublications->contains($commantairePublication)) {
            $this->commantairePublications->add($commantairePublication);
            $commantairePublication->setPublication($this);
        }

        return $this;
    }

    public function removeCommantairePublication(CommantairePublication $commantairePublication): self
    {
        if ($this->commantairePublications->removeElement($commantairePublication)) {
            // set the owning side to null (unless already changed)
            if ($commantairePublication->getPublication() === $this) {
                $commantairePublication->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ReactionPublication>
     */
    public function getReactionPublications(): Collection
    {
        return $this->reactionPublications;
    }

    public function addReactionPublication(ReactionPublication $reactionPublication): self
    {
        if (!$this->reactionPublications->contains($reactionPublication)) {
            $this->reactionPublications->add($reactionPublication);
            $reactionPublication->setPublication($this);
        }

        return $this;
    }

    public function removeReactionPublication(ReactionPublication $reactionPublication): self
    {
        if ($this->reactionPublications->removeElement($reactionPublication)) {
            // set the owning side to null (unless already changed)
            if ($reactionPublication->getPublication() === $this) {
                $reactionPublication->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommantairePublication>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(CommantairePublication $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(CommantairePublication $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */




    public function isLikedByUser(User $user): bool
    {
        Foreach( $this->reactionPublications as $reactionPublication)
        {
            if ($reactionPublication->getUser() === $user) return true;

        }
        return false;


    }


    public function getImageForum(): ?string
    {
        return $this->ImageForum;
    }

    public function setImageForum(string $ImageForum): self
    {
        $this->ImageForum = $ImageForum;

        return $this;
    }

    public function isIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
    public function getUser(): ?User
    {

        return $this->User;
    }
}