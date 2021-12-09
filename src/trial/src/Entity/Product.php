<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use App\Utils\ProductConst;

/**
 * @Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\Table(name="products")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private int $onlinerId;

    /**
     * @ORM\Column(type="string", length=ProductConst::NAME_LENGTH)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", name="link_to_image", length=ProductConst::FULL_LENGTH)
     */
    private string $linkToImage;

    /**
     * @ORM\Column(type="string", length=ProductConst::FULL_LENGTH)
     */
    private string $description;

    /**
     * @ORM\Column(type="string", length=ProductConst::URL_LENGTH)
     */
    private string $url;

    /**
     * @OneToMany(targetEntity="Price", mappedBy="product", cascade={"all"})
     */
    private Collection $prices;

    /**
     * @ORM\ManyToMany(targetEntity="Category", inversedBy="products", cascade={"persist"})
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private Collection $categories;

    /**
     * @ORM\Column(type="date")
     */
    private DateTime $dateLastUpdate;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOnlinerId(): ?int
    {
        return $this->onlinerId;
    }

    public function setOnlinerId(int $onlinerId): self
    {
        $this->onlinerId = $onlinerId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLinkToImage(): ?string
    {
        return $this->linkToImage;
    }

    public function setLinkToImage(string $linkToImage): self
    {
        $this->linkToImage = $linkToImage;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->setProduct($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->removeElement($price)) {
            if ($price->getId() === $this) {
                $price->setId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getDateLastUpdate(): DateTimeInterface
    {
        return $this->dateLastUpdate;
    }

    public function setDateLastUpdate(DateTimeInterface $dateLastUpdate): self
    {
        $this->dateLastUpdate = $dateLastUpdate;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setDateLastUpdateValue(): void
    {
        $this->dateLastUpdate = new DateTime();
    }

    public function getMinPrice(): Price
    {
        return $this->prices->matching((new Criteria())->orderBy([
            'minPrice' => Criteria::ASC
        ]))->first();
    }

    public function isNotUpdatedToday(): bool
    {
        $dateNow = date(ProductConst::DATETIME_FORMAT);

        return $this->dateLastUpdate->format(ProductConst::DATETIME_FORMAT) !== $dateNow;
    }
}