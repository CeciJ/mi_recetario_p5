<?php

namespace App\Controller;

use App\Entity\RecipeIngredients;
use App\Form\RecipeIngredientsType;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecipeIngredientsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/recipe_ingredients")
 */
class RecipeIngredientsController extends AbstractController
{
    private $repository;

    private $em;

    public function __construct(RecipeIngredientsRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/new/{id}", name="recipe_ingredients.new", methods={"GET","POST"})
     */
    public function new(Request $request, RecipeRepository $repo): Response
    {
        $recipeIngredient = new RecipeIngredients();
        $recipeId = $request->attributes->get('id');
        $recipe = $repo->find($recipeId);
        $recipeIngredient->setRecipe($recipe);
        $ingredients = $recipe->getRecipeIngredients();

        $form = $this->createForm(RecipeIngredientsType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipeIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_ingredients.new', [
                'id' => $recipeId,
            ]);
        }

        return $this->render('recipe_ingredients/new.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form->createView(),
            'ingredients' => $ingredients
        ]);
    }

    /**
     * @Route("/{id}/edit", name="recipe_ingredients.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RecipeIngredients $recipeIngredient): Response
    {
        $form = $this->createForm(RecipeIngredientsType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('recipe_ingredients.index', [
                'id' => $recipeIngredient->getId(),
            ]);
        }

        return $this->render('recipe_ingredients/edit.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="recipe_ingredients.delete", methods={"DELETE"})
     */
    public function delete(Request $request, RecipeIngredients $recipeIngredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipeIngredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipeIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_ingredients.index');
    }
}
