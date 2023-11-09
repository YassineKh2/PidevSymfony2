<?php

namespace App\Entity;

use App\Repository\PannierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PannierRepository::class)]
class Pannier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToOne(inversedBy: 'pannier', cascade: ['persist', 'remove'])]
    private ?Utilisateur $client = null;

    #[ORM\Column]
    private ?float $PrixTotalPainner = null;

    #[ORM\OneToMany(mappedBy: 'pannier', targetEntity: Article::class)]
    private Collection $Article;

    public function __construct()
    {
        $this->Article = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getPrixTotalPainner(): ?float
    {
        return $this->PrixTotalPainner;
    }

    public function setPrixTotalPainner(float $PrixTotalPainner): self
    {
        $this->PrixTotalPainner = $PrixTotalPainner;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->Article;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->Article->contains($article)) {
            $this->Article->add($article);
            $article->setPannier($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->Article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getPannier() === $this) {
                $article->setPannier(null);
            }
        }

        return $this;
    }

}
