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
        dump($search); 
        $startDate = $search->getStartPeriod();
        $endDate = $search->getEndPeriod();

        dump($tabNames); 
        $query = $this->createQueryBuilder('ri');

        if(empty($endDate)){
            $query = $query 
            //Jointure sur Recipe
            ->select('ri', 'SUM(ri.quantity) as quantity', 'i.name', 'u.unit as unit')
            ->innerJoin('ri.recipe', 'r')
            ->addSelect('r.id')
            //Jointure sur Ingredient
            ->innerJoin('ri.nameIngredient', 'i')
            ->addSelect('i')
            //Jointure sur Unit
            ->innerJoin('ri.unit', 'u')
            ->addSelect('u')
            //Jointure sur MealPlanning
            ->innerJoin('r.mealPlannings', 'mp')
            ->addSelect('mp')
            //Conditions
            ->andWhere('ri.nameIngredient IN(:tabNames)')
            //->andWhere('mp.beginAt BETWEEN :begin_at AND :end_at')
            ->andWhere('mp.beginAt >= :begin_at')
            ->setParameter('begin_at', $startDate)
            //->setParameter('end_at', $endDate)
            ->setParameter('tabNames', $tabNames)
            //->addGroupBy('ri');
            ->addGroupBy('ri.nameIngredient');
        }
        else {
            $query = $query 
            //Jointure sur Recipe
            ->select('ri', 'SUM(ri.quantity) as quantity', 'i.name', 'u.unit')
            ->innerJoin('ri.recipe', 'r')
            ->addSelect('r')
            //Jointure sur Ingredient
            ->innerJoin('ri.nameIngredient', 'i')
            ->addSelect('i')
            //Jointure sur Unit
            ->innerJoin('ri.unit', 'u')
            ->addSelect('u')
            //Jointure sur MealPlanning
            ->innerJoin('r.mealPlannings', 'mp')
            ->addSelect('mp')
            //Conditions
            ->andWhere('ri.nameIngredient IN(:tabNames)')
            ->andWhere('mp.beginAt BETWEEN :begin_at AND :end_at')
            //->andWhere('mp.beginAt >= :begin_at')
            ->setParameter('begin_at', $startDate)
            ->setParameter('end_at', $endDate)
            ->setParameter('tabNames', $tabNames)
            //->addGroupBy('ri');
            ->addGroupBy('ri.nameIngredient');
        }

        return $query->getQuery()->execute();
        
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
