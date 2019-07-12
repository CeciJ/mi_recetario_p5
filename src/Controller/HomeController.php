<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\DishTypeRepository;
use App\Entity\DishType;

class HomeController extends AbstractController 
{

    /**
     * @Route("/", name="home")
     * @param RecipeRepository $repository
     * @return Response
     */
    public function index(RecipeRepository $repository, DishTypeRepository $dishTypeRepository): Response
    {
        $recipes = $repository->findLatest();
        $allRecipes = $repository->findAll();
        $foodCategories = $dishTypeRepository->findAll();
        $recipeCategories = [];
        $foodAllCategories = [];

        foreach($foodCategories as $foodCategory){
            $idCategory = $foodCategory->getId();
            $foodAllCategories[] = $idCategory;
            $recipesByCategories = $repository->findByRecipeCategory($idCategory);
            $recipeCategories[] = $recipesByCategories;
        }
        $recipesByAllCategories = array_combine($foodAllCategories, $recipeCategories);

        return $this->render("pages/home.html.twig", [
            'recipes' => $recipes,
            'foodCategories' => $foodCategories,
            'allRecipes' => $allRecipes,
            'recipeCategories' => $recipesByAllCategories
        ]);
    }

}