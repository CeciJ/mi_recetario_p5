<?php

namespace App\Repository;

use App\Entity\RecipeIngredients;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
    public function findByRecipeId($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere(":recipeId MEMBER OF r.recipeId")
            ->setParameter("recipeId", $value)
            //->orderBy('r.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

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
