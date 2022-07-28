<?php

namespace App\DataFixtures;

use App\Entity\Accessories;
use Doctrine\Persistence\ObjectManager;

class AccessoriesFixtures extends AppFixtures
{
    const ACCESSORIES = [
        "sun roof",
        "auto mirror",
        "apple car play",
        "keyless entry",
        "ABS",
    ];
    public function loadData(ObjectManager $manager): void
    {
        $index = 0;
        $this->createMany(
            Accessories::class,
            count(self::ACCESSORIES),
            function (Accessories $entity) use (&$index) {
                $accessory = self::ACCESSORIES[$index];
                $entity->setName($accessory);
                $this->timestampable($entity);
                $index++;
            }
        );
    }
}
