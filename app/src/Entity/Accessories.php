<?php

namespace App\Entity;

use App\Repository\AccessoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AccessoriesRepository::class)]
class Accessories
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("CARS::READ")]
    private ?string $name = null;

//    #[ORM\ManyToMany(targetEntity: Car::class, mappedBy: 'accessories')]
//    private Collection $cars;

//    public function __construct()
//    {
//        $this->cars = new ArrayCollection();
//    }

    public function getId(): ?int
    {
        return $this->id;
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

//    /**
//     * @return Collection<int, Car>
//     */
//    public function getCars(): Collection
//    {
//        return $this->cars;
//    }
//
//    public function addCar(Car $car): self
//    {
//        if (!$this->cars->contains($car)) {
//            $this->cars->add($car);
//            $car->addAccessory($this);
//        }
//
//        return $this;
//    }
//
//    public function removeCar(Car $car): self
//    {
//        if ($this->cars->removeElement($car)) {
//            $car->removeAccessory($this);
//        }
//
//        return $this;
//    }
}
