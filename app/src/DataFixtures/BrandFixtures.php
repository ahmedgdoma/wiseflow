<?php

namespace App\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use App\Entity\Brand;

class BrandFixtures extends AppFixtures
{
    const CARS = [
        "Skoda",
        "BMW",
        "Toyota",
        "Hyundai",
        "Nissan",
        "MG",
    ];
    public function loadData(ObjectManager $manager): void
    {
        $index = 0;
        $this->createMany(
            Brand::class,
            count(self::CARS),
            function (Brand $entity) use (&$index) {
                $car = self::CARS[$index];
                $entity->setName($car);
                 $this->timestampable($entity);
                $index++;
            }
        );
    }
}
