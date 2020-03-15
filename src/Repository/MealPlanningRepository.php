<?php

namespace App\Repository;

use App\Entity\ListSearch;
use App\Entity\MealPlanning;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MealPlanning|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealPlanning|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealPlanning[]    findAll()
 * @method MealPlanning[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealPlanningRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MealPlanning::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(ListSearch $search)
    {
        $startDate = $search->getStartPeriod();
        $endDate = $search->getEndPeriod();

        $query = $this->createQueryBuilder('r');

        if(isset($startDate) && empty($endDate)){
            $query = $query 
                ->andWhere('r.beginAt >= :begin_at')
                ->setParameter('begin_at', $startDate)
                ->orderBy('r.beginAt', 'ASC');
        }
        elseif (isset($startDate) && isset($endDate)){
            $query = $query
                ->andWhere('r.beginAt BETWEEN :begin_at AND :end_at')
                ->setParameter('begin_at', $startDate)
                ->setParameter('end_at', $endDate)
                ->orderBy('r.beginAt', 'ASC');
        }
        
        return $query->getQuery()->execute();
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
