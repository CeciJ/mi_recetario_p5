<?php

namespace App\Repository;

use App\Entity\FoodType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FoodType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FoodType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FoodType[]    findAll()
 * @method FoodType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FoodTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FoodType::class);
    }

    // /**
    //  * @return FoodType[] Returns an array of FoodType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FoodType
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
