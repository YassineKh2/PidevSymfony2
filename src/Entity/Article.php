<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("Article")]
        private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[Assert\NotBlank(message:"Categorie est requise")]
    #[Groups("Article")]
    private ?Categorie $Categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Nom de l'Article est requis")]
    #[Assert\Length(min: 3,max: 30)]
    #[Groups("Article")]
    private ?string $NomArticle = null;

    #[ORM\Column]
    #[Assert\Positive(message:"Prix de l'article doit etre positive")]
    #[Assert\NotBlank(message:"Prix de l'article est requis")]
    #[Groups("Article")]
    private ?float $PrixArticle = null;

    #[ORM\Column]
    #[Assert\Positive(message:"Quantite doit etre positive")]
    #[Assert\NotBlank(message:"Quantite est requis")]
    #[Groups("Article")]
    private ?int $QuantiteArticle = null;


    #[ORM\Column(length: 255)]
    #[Assert\Image(
        minWidth: 300,
        maxWidth: 10000,
        maxHeight: 10000,
        minHeight: 300,
    )]
    #[Groups("Article")]
    private ?string $ImageArticle = null;

    #[ORM\Column(length: 1000)]
    #[Assert\NotBlank(message:"La discription est requise")]
    #[Assert\Length(min: 5)]
    #[Groups("Article")]
    private ?string $ArticleDiscription = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Positive(message:"Remise doit etre positive")]
    #[Groups("Article")]
    private ?int $RemisePourcentageArticle = null;

   // #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'Wishlist')]
    #[Groups("Article")]
    private Collection $utilisateurs;

    #[ORM\ManyToMany(targetEntity: Payment::class, mappedBy: 'Articles')]
    #[Groups("Article")]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'Article', targetEntity: Rating::class)]
    private Collection $ratings;

    #[ORM\OneToMany(mappedBy: 'Article', targetEntity: ColorArticle::class,cascade: ["remove","persist"])]
    private Collection $colorArticles;

    #[ORM\OneToMany(mappedBy: 'Article', targetEntity: SizeArticle::class,cascade: ["remove","persist"])]
    private Collection $sizeArticles;

    #[ORM\Column(nullable: true)]
    private ?int $SaleNumberArticle = null;


    private ?string $Size = null;
    private ?string $Color = null;

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->Size;
    }

    /**
     * @param string|null $Size
     */
    public function setSize(?string $Size): void
    {
        $this->Size = $Size;
    }

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->Color;
    }

    /**
     * @param string|null $Color
     */
    public function setColor(?string $Color): void
    {
        $this->Color = $Color;
    }



    public function __construct()
    {
        $this->utilisateurs = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->colorArticles = new ArrayCollection();
        $this->sizeArticles = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getNomArticle(): ?string
    {
        return $this->NomArticle;
    }

    public function setNomArticle(?string $NomArticle): self
    {
        $this->NomArticle = $NomArticle;

        return $this;
    }

    public function getPrixArticle(): ?float
    {
        return $this->PrixArticle;
    }

    public function setPrixArticle(float $PrixArticle): self
    {
        $this->PrixArticle = $PrixArticle;

        return $this;
    }

    public function getQuantiteArticle(): ?int
    {
        return $this->QuantiteArticle;
    }

    public function setQuantiteArticle(int $QuantiteArticle): self
    {
        $this->QuantiteArticle = $QuantiteArticle;

        return $this;
    }

    public function getImageArticle(): ?string
    {
        return $this->ImageArticle;
    }

    public function setImageArticle(?string $ImageArticle): self
    {
        $this->ImageArticle = $ImageArticle;

        return $this;
    }

    public function getArticleDiscription(): ?string
    {
        return $this->ArticleDiscription;
    }

    public function setArticleDiscription(string $ArticleDiscription): self
    {
        $this->ArticleDiscription = $ArticleDiscription;

        return $this;
    }

    public function getRemisePourcentageArticle(): ?int
    {
        return $this->RemisePourcentageArticle;
    }

    public function setRemisePourcentageArticle(?int $RemisePourcentageArticle): self
    {
        $this->RemisePourcentageArticle = $RemisePourcentageArticle;

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
            $utilisateur->addWishlist($this);
        }

        return $this;
    }

    public function removeUtilisateur(Utilisateur $utilisateur): self
    {
        if ($this->utilisateurs->removeElement($utilisateur)) {
            $utilisateur->removeWishlist($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->addArticle($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            $payment->removeArticle($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setArticle($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getArticle() === $this) {
                $rating->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ColorArticle>
     */
    public function getColorArticles(): Collection
    {
        return $this->colorArticles;
    }

    public function addColorArticle(ColorArticle $colorArticle): self
    {
        if (!$this->colorArticles->contains($colorArticle)) {
            $this->colorArticles->add($colorArticle);
            $colorArticle->setArticle($this);
        }

        return $this;
    }

    public function removeColorArticle(ColorArticle $colorArticle): self
    {
        if ($this->colorArticles->removeElement($colorArticle)) {
            // set the owning side to null (unless already changed)
            if ($colorArticle->getArticle() === $this) {
                $colorArticle->setArticle(null);
            }
        }

        return $this;
    }
    public function removeAllColorArticles(): self
    {
        foreach ($this->colorArticles as $colorArticle) {
            // set the owning side to null (unless already changed)
            if ($colorArticle->getArticle() === $this) {
                $colorArticle->setArticle(null);
            }
        }
        $this->colorArticles = new ArrayCollection();
        return $this;
    }
    /**
     * @return Collection<int, SizeArticle>
     */
    public function getSizeArticles(): Collection
    {
        return $this->sizeArticles;
    }

    public function addSizeArticle(SizeArticle $sizeArticle): self
    {
        if (!$this->sizeArticles->contains($sizeArticle)) {
            $this->sizeArticles->add($sizeArticle);
            $sizeArticle->setArticle($this);
        }

        return $this;
    }

    public function removeSizeArticle(SizeArticle $sizeArticle): self
    {
        if ($this->sizeArticles->removeElement($sizeArticle)) {
            // set the owning side to null (unless already changed)
            if ($sizeArticle->getArticle() === $this) {
                $sizeArticle->setArticle(null);
            }
        }

        return $this;
    }

    public function removeAllSizeArticles(): self
    {
        foreach ($this->sizeArticles as $sizeArticle) {
            // set the owning side to null (unless already changed)
            if ($sizeArticle->getArticle() === $this) {
                $sizeArticle->setArticle(null);
            }
        }
        $this->sizeArticles = new ArrayCollection();
        return $this;
    }


    public function getSaleNumberArticle(): ?int
    {
        return $this->SaleNumberArticle;
    }

    public function setSaleNumberArticle(?int $SaleNumberArticle): self
    {
        $this->SaleNumberArticle = $SaleNumberArticle;

        return $this;
    }




}
