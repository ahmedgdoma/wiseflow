<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function add(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Car $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Car[] Returns an array of Car objects
     */
    public function getCars($parameters): array
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.brand', 'b')
            ->leftJoin('c.accessories', 'a');
        if (!empty($parameters['filters']))
            $this->addFilters($qb, $parameters['filters']);
        if (!empty($parameters['sort']))
            $this->addSort($qb, $parameters['sort']);

        return $qb->getQuery()
            ->getResult();
    }


    /**
     * @param QueryBuilder $queryBuilder
     * @param array $filters
     */
    private function addFilters(QueryBuilder $queryBuilder, array $filters): void
    {

        foreach ($filters as $property => $data) {
            $i = 1;
            foreach ($data as $operation => $value) {
                $param = $property . $i++;
                $queryBuilder->andWhere(
                    call_user_func(array($queryBuilder->expr(), $operation), 'c.' . $property, ':' . $param)
                );
                $queryBuilder->setParameter($param, $value);
            }
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $sort
     */
    private function addSort(QueryBuilder $queryBuilder, array $sort): void
    {
        $vars = Car::getVars();
        foreach ($sort as $key => $value) {
            if (in_array($key, $vars)) {
                $property = 'c.' . $key;
                $order = (strtolower($value) == 'dsc') ? 'DSC' : 'ASC';
                $queryBuilder->addOrderBy($property, $order);
            }
        }
    }
}
