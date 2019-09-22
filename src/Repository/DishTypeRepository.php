<?php

namespace App\Repository;

use App\Entity\DishType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method DishType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DishType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DishType[]    findAll()
 * @method DishType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DishTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DishType::class);
    }

    // /**
    //  * @return DishType[] Returns an array of DishType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DishType
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
