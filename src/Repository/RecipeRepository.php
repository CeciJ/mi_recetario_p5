<?php

namespace App\Repository;

use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\RecipeSearch;

/**
 * @method Recipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipe[]    findAll()
 * @method Recipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Recipe::class);
    }

    /**
     * @return Query
     */
    public function findAllQuery(RecipeSearch $search)
    {
        $query = $this->createQueryBuilder('r');

        if($search->getMaxTotalTime())
        {
            $query = $query
                ->andWhere('r.totalTime <= :maxTotalTime')
                ->setParameter('maxTotalTime', $search->getMaxTotalTime());
        }

        if($search->getNumberPersons())
        {
            $query = $query
                ->andWhere('r.numberPersons = :numberPersons')
                ->setParameter('numberPersons', $search->getNumberPersons());
        }

        if($search->getDishTypes()->count() > 0)
        {
            $k = 0;
            foreach($search->getDishTypes() as $k => $DishType)
            {
                $k++;
                $query = $query
                ->andWhere(":DishType$k MEMBER OF r.DishTypes")
                ->setParameter("DishType$k", $DishType);
            }
            
        }

        if($search->getFoodTypes()->count() > 0)
        {
            $k = 0;
            foreach($search->getFoodTypes() as $k => $foodType)
            {
                $k++;
                $query = $query
                ->andWhere(":foodType$k MEMBER OF r.foodTypes")
                ->setParameter("foodType$k", $foodType);
            }
            
        }

        if($search->getOptions()->count() > 0)
        {
            $k = 0;
            foreach($search->getOptions() as $k => $option)
            {
                $k++;
                $query = $query
                ->andWhere(":option$k MEMBER OF r.options")
                ->setParameter("option$k", $option);
            }
            
        }

        return $query->getQuery();
    }

    /**
     * @return Recipe[] Returns an array of Recipe objects
     */
    public function findLatest()
    {
        return $this->createQueryBuilder('r')
                    ->setMaxResults(4)
                    ->getQuery()
                    ->getResult();
    }

    /**
      * @return RecipeIngredients[] Returns an array of RecipeIngredients objects
      */
      public function findByRecipeCategory($value)
      {
          return $this->createQueryBuilder('r')
              ->andWhere(":recipeCategory MEMBER OF r.DishTypes")
              ->setParameter("recipeCategory", $value)
              //->orderBy('r.id', 'ASC')
              //->setMaxResults(10)
              ->getQuery()
              ->getResult()
          ;
      }

    /*
    public function findOneBySomeField($value): ?Recipe
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
