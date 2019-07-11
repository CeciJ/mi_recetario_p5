<?php

namespace App\Repository;

use App\Entity\MealPlanning;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method MealPlanning|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealPlanning|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealPlanning[]    findAll()
 * @method MealPlanning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealPlanningRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, MealPlanning::class);
    }

    // /**
    //  * @return MealPlanning[] Returns an array of MealPanning objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MealPlanning
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
