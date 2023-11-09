<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $CodeCoupon = null;

    #[ORM\Column]
    private ?float $PourcentageCoupon = null;

    #[ORM\Column(nullable: true)]
    private ?int $NumberOfUsages = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCoupon(): ?string
    {
        return $this->CodeCoupon;
    }

    public function setCodeCoupon(string $CodeCoupon): self
    {
        $this->CodeCoupon = $CodeCoupon;

        return $this;
    }

    public function getPourcentageCoupon(): ?float
    {
        return $this->PourcentageCoupon;
    }

    public function setPourcentageCoupon(float $PourcentageCoupon): self
    {
        $this->PourcentageCoupon = $PourcentageCoupon;

        return $this;
    }

    public function getNumberOfUsages(): ?int
    {
        return $this->NumberOfUsages;
    }

    public function setNumberOfUsages(?int $NumberOfUsages): self
    {
        $this->NumberOfUsages = $NumberOfUsages;

        return $this;
    }
}
