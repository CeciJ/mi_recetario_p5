<?php

namespace App\Repository;

use App\Entity\CorrespondingWeightsUnities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CorrespondingWeightsUnities|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorrespondingWeightsUnities|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorrespondingWeightsUnities[]    findAll()
 * @method CorrespondingWeightsUnities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrespondingWeightsUnitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CorrespondingWeightsUnities::class);
    }

    // /**
    //  * @return CorrespondingWeightsUnities[] Returns an array of CorrespondingWeightsUnities objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CorrespondingWeightsUnities
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
