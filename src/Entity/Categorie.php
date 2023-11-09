<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Categorie")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Nom de le categorie est requis")]
    #[Assert\Length(min: 3,max: 30)]
    #[Groups("Categorie")]
    private ?string $NomCategorie = null;

    #[ORM\OneToMany(mappedBy: 'Categorie', targetEntity: Article::class, cascade: ["remove","persist"])]
    #[Groups("Categorie")]
    private Collection $articles;

    #[ORM\Column(length: 255)]
    #[Assert\Image(
        minWidth: 500,
        maxWidth: 10000,
        maxHeight: 10000,
        minHeight: 500,
    )]
    #[Groups("Categorie")]
    private ?string $ImageCategorie = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCategorie(): ?string
    {
        return $this->NomCategorie;
    }

    public function setNomCategorie(?string $NomCategorie): self
    {
        $this->NomCategorie = $NomCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setCategorie($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategorie() === $this) {
                $article->setCategorie(null);
            }
        }

        return $this;
    }

    public function getImageCategorie(): ?string
    {
        return $this->ImageCategorie;
    }

    public function setImageCategorie(string $ImageCategorie): self
    {
        $this->ImageCategorie = $ImageCategorie;

        return $this;
    }
}
