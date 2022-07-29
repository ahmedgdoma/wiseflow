<?php

namespace App\Entity;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\NumericFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\RangeFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Elasticsearch\DataProvider\Filter\TermFilter;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiFilter;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiFilter(NumericFilter::class, properties: ['model'])]
#[ApiFilter(RangeFilter::class, properties: ['model'])]
#[ApiFilter(SearchFilter::class, properties: [ 'color' => 'exact', 'brand.name' => 'partial'])]
#[ApiFilter(SearchFilter::class, properties: ['accessories.name'])]
#[ApiFilter(BooleanFilter::class, properties: ['gasEconomy'])]
#[ApiFilter(DateFilter::class, properties: ['createdAt'])]
#[ApiFilter(OrderFilter::class, properties: ['id' => 'ASC', 'model' => 'ASC', 'createdAt' => 'ASC'])]
class Car
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("CARS::READ")]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups("CARS::READ")]
    private ?int $model = null;

    #[ORM\Column(length: 50)]
    private ?string $color = null;

    #[ORM\Column]
    private ?bool $gasEconomy = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups("CARS::READ")]
    private ?Brand $brand = null;

    #[ORM\ManyToMany(targetEntity: Accessories::class)]
    #[Groups("CARS::READ")]
    private Collection $accessories;

    public function __construct()
    {
        $this->accessories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?int
    {
        return $this->model;
    }

    public function setModel(int $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function isGasEconomy(): ?bool
    {
        return $this->gasEconomy;
    }

    public function setGasEconomy(bool $gasEconomy): self
    {
        $this->gasEconomy = $gasEconomy;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return Collection<int, Accessories>
     */
    public function getAccessories(): Collection
    {
        return $this->accessories;
    }

    public function addAccessory(Accessories $accessory): self
    {
        if (!$this->accessories->contains($accessory)) {
            $this->accessories->add($accessory);
        }

        return $this;
    }

    public function removeAccessory(Accessories $accessory): self
    {
        $this->accessories->removeElement($accessory);

        return $this;
    }
}
