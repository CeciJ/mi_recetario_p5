<?php

namespace App\Repository;

use App\Entity\MeasureUnit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MeasureUnit|null find($id, $lockMode = null, $lockVersion = null)
 * @method MeasureUnit|null findOneBy(array $criteria, array $orderBy = null)
 * @method MeasureUnit[]    findAll()
 * @method MeasureUnit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeasureUnitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeasureUnit::class);
    }

    public function findAllDistinct()
    {
        return $this->createQueryBuilder('m')
            ->select('DISTINCT m.unit')
            //->distinct('name')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return MeasureUnit[] Returns an array of MeasureUnit objects
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
    public function findOneBySomeField($value): ?MeasureUnit
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
