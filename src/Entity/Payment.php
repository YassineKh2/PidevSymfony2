<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?Utilisateur $IdUtilisateur = null;

    #[ORM\ManyToMany(targetEntity: Article::class, inversedBy: 'payments')]
    private Collection $Articles;

    #[ORM\Column]
    private ?float $PrixTotal = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DatePayment = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    private ?User $IdUser = null;

    public function __construct()
    {
        $this->Articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->IdUtilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $IdUtilisateur): self
    {
        $this->IdUtilisateur = $IdUtilisateur;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->Articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->Articles->contains($article)) {
            $this->Articles->add($article);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        $this->Articles->removeElement($article);

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->PrixTotal;
    }

    public function setPrixTotal(float $PrixTotal): self
    {
        $this->PrixTotal = $PrixTotal;

        return $this;
    }

    public function getDatePayment(): ?\DateTimeInterface
    {
        return $this->DatePayment;
    }

    public function setDatePayment(\DateTimeInterface $DatePayment): self
    {
        $this->DatePayment = $DatePayment;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->IdUser;
    }

    public function setIdUser(?User $IdUser): self
    {
        $this->IdUser = $IdUser;

        return $this;
    }
}
