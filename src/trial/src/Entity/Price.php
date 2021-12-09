<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use App\Utils\PriceConst;

/**
 * @Entity(repositoryClass="App\Repository\PriceRepository")
 * @ORM\Table(name="prices")
 * @ORM\HasLifecycleCallbacks()
 */
class Price
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="float", name="min_price")
     */
    private float $minPrice;

    /**
     * @ORM\Column(type="float", name="max_price")
     */
    private float $maxPrice;

    /**
     * @ORM\Column(type="float", name="avg_price")
     */
    private float $avgPrice;

    /**
     * @ORM\Column(type="string", nullable=true, length=PriceConst::CURRENCY_LENGTH)
     */
    private string $currency;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="prices", cascade={"all"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    private Product $product;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getMinPrice(): float
    {
        return $this->minPrice;
    }

    /**
     * @param float $minPrice
     * @return Price
     */
    public function setMinPrice(float $minPrice): self
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getMaxPrice(): float
    {
        return $this->maxPrice;
    }

    /**
     * @param float $maxPrice
     * @return Price
     */
    public function setMaxPrice(float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * @return float
     */
    public function getAvgPrice(): float
    {
        return $this->avgPrice;
    }

    /**
     * @param float $avgPrice
     * @return Price
     */
    public function setAvgPrice(float $avgPrice): self
    {
        $this->avgPrice = $avgPrice;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     * @return Price
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getDate(): DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateValue(): void
    {
        $this->date = new DateTime();
    }
}