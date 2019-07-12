<?php

namespace App\Controller\Admin;

use App\Entity\RecipeIngredients;
use App\Form\RecipeIngredientsType;
use App\Repository\RecipeIngredientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/recipe/ingredients")
 */
class AdminRecipeIngredientsController extends AbstractController
{
    /**
     * @Route("/", name="admin.recipe_ingredients.index", methods={"GET"})
     */
    public function index(RecipeIngredientsRepository $recipeIngredientsRepository, Request $request): Response
    {
        $recipeId = $request->attributes->get('id');
        $recipeName = $request->attributes->get('name');
        
        return $this->render('admin/recipe_ingredients/index.html.twig', [
            'recipe_ingredients' => $recipeIngredientsRepository->findByRecipeId($recipeId),
            'recipe_name' => $recipeName,
            'recipe_id' => $recipeId
        ]);
    }

    /**
     * @Route("/{id}/{name}/new", name="admin.recipe_ingredients.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $recipeIngredient = new RecipeIngredients();
        $recipeId = (int)$request->attributes->get('id');
        $recipeName = $request->attributes->get('name');

        $form = $this->createForm(RecipeIngredientsType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($recipeIngredient);
            $entityManager->flush();

            return $this->redirectToRoute('admin.recipe_ingredients.index', [
                'id' => $recipeId,
                'name' => $recipeName
            ]);
        }

        return $this->render('admin/recipe_ingredients/new.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'recipe_id' => $recipeId,
            'recipe_name' => $recipeName,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{idRecipe}/{name}/edit/{id}", name="admin.recipe_ingredients.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RecipeIngredients $recipeIngredient): Response
    {
        $recipeId = (int)$request->attributes->get('idRecipe');
        $recipeName = $request->attributes->get('name');
        $recipeIngredientId = (int)$request->attributes->get('id');
        
        $form = $this->createForm(RecipeIngredientsType::class, $recipeIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.recipe_ingredients.index', [
                'id' => $recipeId,
                'name' => $recipeName
            ]);
        }

        return $this->render('admin/recipe_ingredients/edit.html.twig', [
            'recipe_ingredient' => $recipeIngredient,
            'recipe_id' => $recipeId,
            'recipe_name' => $recipeName,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{idRecipe}/{name}/delete/{id}", name="admin.recipe_ingredients.delete", methods={"DELETE"})
     */
    public function delete(Request $request, RecipeIngredients $recipeIngredient): Response
    {
        $recipeId = (int)$request->attributes->get('idRecipe');
        $recipeName = $request->attributes->get('name');
        $recipeIngredientId = (int)$request->attributes->get('id');

        if ($this->isCsrfTokenValid('delete'.$recipeIngredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipeIngredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.recipe_ingredients.index', [
                'id' => $recipeId,
                'name' => $recipeName
            ]);
    }
}
