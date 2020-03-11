<?php

namespace App\Repository;

use App\Entity\ListSearch;
use App\Entity\RecipeIngredients;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method RecipeIngredients|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecipeIngredients|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecipeIngredients[]    findAll()
 * @method RecipeIngredients[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeIngredientsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RecipeIngredients::class);
    }

    /**
    * @return RecipeIngredients[] Returns an array of RecipeIngredients objects
    */
    public function compileIngredients(ListSearch $search, $tabNames, $allIngredients)
    {
        $startDate = $search->getStartPeriod();
        $endDate = $search->getEndPeriod();
    }

    // /**
    //  * @return RecipeIngredients[] Returns an array of RecipeIngredients objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecipeIngredients
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
