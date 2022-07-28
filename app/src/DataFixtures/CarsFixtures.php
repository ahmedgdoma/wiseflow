<?php

namespace App\DataFixtures;

use App\Entity\Accessories;
use App\Entity\Brand;
use App\Entity\Car;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CarsFixtures extends AppFixtures implements DependentFixtureInterface
{
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(
            Car::class,
            100,
            function (Car $entity){
                $entity->setModel($this->faker->year());
                $entity->setColor($this->faker->colorName());
                $entity->setGasEconomy($this->faker->boolean());
                $entity->setBrand($this->getRandomReference(Brand::class));
                $n = $this->faker->numberBetween(0, 4);
                for ($i = 0;$i<$n;$i++){
                    $entity->addAccessory($this->getRandomReference(Accessories::class));
                }
                $this->timestampable($entity);
            }
        );
    }

    public function getDependencies()
    {
        return [
            AccessoriesFixtures::class,
            BrandFixtures::class
        ];
    }
}
