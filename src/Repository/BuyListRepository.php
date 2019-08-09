<?php

namespace App\Repository;

use App\Entity\BuyList;
use App\Entity\ListSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method BuyList|null find($id, $lockMode = null, $lockVersion = null)
 * @method BuyList|null findOneBy(array $criteria, array $orderBy = null)
 * @method BuyList[]    findAll()
 * @method BuyList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuyListRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BuyList::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(ListSearch $search)
    {
        $query = $this->createQueryBuilder('r');

        if($search->getStartPeriod())
        {
            $query = $query
                ->andWhere('r.startPeriod >= :startPeriod')
                ->setParameter('startPeriod', $search->getStartPeriod());
        }

        return $query->getQuery();
    }

    // /**
    //  * @return BuyList[] Returns an array of BuyList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BuyList
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
