<?php

namespace App\Entity;

use App\Repository\CommantairePublicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommantairePublicationRepository::class)]
class CommantairePublication
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("commentaires")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commantairePublications')]
    private ?Publication $Publication ;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups("commentaires")]
    private ?\DateTimeInterface $DateCommantaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:" ce champs est requis")]
    #[Groups("commentaires")]
    private ?string $ContenuCommantaire = null;

    #[ORM\ManyToOne(inversedBy: 'commantairePublications')]
    private ?User $User = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPublication(): ?Publication
    {
        return $this->Publication;
    }

    public function setPublication(?Publication $Publication): self
    {
        $this->Publication = $Publication;

        return $this;
    }

    public function getDateCommantaire(): ?\DateTimeInterface
    {
        return $this->DateCommantaire;
    }

    public function setDateCommantaire(\DateTimeInterface $DateCommantaire): self
    {
        $this->DateCommantaire = $DateCommantaire;

        return $this;
    }

    public function getContenuCommantaire(): ?string
    {
        return $this->ContenuCommantaire;
    }

    public function setContenuCommantaire(string $ContenuCommantaire): self
    {
        $this->ContenuCommantaire = $ContenuCommantaire;

        return $this;
    }
    public function getTexte(): ?string
    {
        return $this->texte;
    }
    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}