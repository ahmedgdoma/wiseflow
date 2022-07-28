<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\{Factory, Generator};

abstract class AppFixtures extends Fixture
{
    protected Generator   $faker;
    private ObjectManager $manager;
    private array         $referencesIndex = [];

    abstract protected function loadData(ObjectManager $manager);

    public function load(ObjectManager $manager)
    {
        $this->faker       = Factory::create();
        $this->manager     = $manager;
        $this->loadData($manager);
        $manager->flush();
    }

    protected function createMany(
        string $className,
        int $count,
        callable $factory,
        string $referencesStorage = null,
        int $i = 0,
        int &$counter = null,
    ): array
    {
        $createdEntities = [];
        if ($referencesStorage === null) {
            $referencesStorage = $className;
        }
        for (;$i < $count; $i++) {
            if ($counter === null) {
                $counter = $i;
            } else {
                $counter++;
            }
            $entity = new $className();
            $callableOutput = $factory($entity, $i);
            if ($callableOutput === 1) {
                $i--;
                continue;
            }
            if ($callableOutput === false) {
                continue;
            }
            $this->manager->persist($entity);
            $this->addReference($referencesStorage.'_'.$counter, $entity);
            $createdEntities[$i] = $entity;
        }

        return $createdEntities;
    }

    /**
     * @throws \Exception
     */
    protected function getRandomReference(string $storage, bool $unique = false, $ignoreReference = null): object
    {
        if (!isset($this->referencesIndex[$storage])) {
            $this->referencesIndex[$storage] = [];
            foreach ($this->referenceRepository->getReferences() as $key => $ref) {
                if (str_starts_with($key, $storage.'_')) {
                    $this->referencesIndex[$storage][] = $key;
                }
            }
        }
        if (empty($this->referencesIndex[$storage])) {
            throw new \Exception(sprintf('Cannot find any references for storage: %s', $storage));
        }
        $references = $this->referencesIndex[$storage];
        if ($ignoreReference !== null) {
            unset($references[array_search($ignoreReference, $references, true)]);
        }
        $randomReferenceKey = $this->faker->randomElement($references);
        if ($unique) {
            unset(
                $this->referencesIndex[$storage][array_search(
                    $randomReferenceKey,
                    $this->referencesIndex[$storage],
                    true
                )]
            );
        }

        return $this->getReference($randomReferenceKey);
    }

    protected function timestampable($entity): void
    {
        $updatedAt = $this->faker->dateTimeThisYear();
        $createdAt = $this->faker->dateTimeThisYear($updatedAt->format(DATE_ATOM));
        $entity->setCreatedAt($createdAt);
        $entity->setUpdatedAt($updatedAt);

    }

}
