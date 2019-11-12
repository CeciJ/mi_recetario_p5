<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use Algolia\AlgoliaSearch\SearchClient;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Algolia\SearchBundle\IndexManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ingredient")
 */
class IngredientController extends AbstractController
{
    protected $indexManager;

    public function __construct(IndexManagerInterface $indexingManager)
    {
        $this->indexManager = $indexingManager;
    }
    
    /**
     * @Route("/", name="admin.ingredient.index", methods={"GET", "POST"})
     */
    public function index(IngredientRepository $ingredientRepository, Request $request): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('admin.ingredient.index');
        }

        return $this->render('ingredient/index.html.twig', [
            'ingredients' => $ingredientRepository->findAll(),
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="admin.ingredient.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $ingredient->getName();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ingredient);
            $entityManager->flush();

            var_dump($this->indexManager); die();

            $this->indexManager->index($ingredient, $entityManager);

            /* $client = \Algolia\AlgoliaSearch\SearchClient::create('D4T2HAD5AA', '72fce73f2ab1a76a00144fe0952c0923');
            $index = $client->initIndex('ingredients');
            $index->saveObject(
                [
                  'name' => $name
                ], 
                ['autoGenerateObjectIDIfNotExist' => true]
            ); */

            return $this->redirectToRoute('admin.ingredient.index');
        }

        return $this->render('ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.ingredient.show", methods={"GET"})
     */
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.ingredient.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ingredient $ingredient): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.ingredient.index', [
                'id' => $ingredient->getId(),
            ]);
        }

        return $this->render('ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.ingredient.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ingredient $ingredient): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ingredient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.ingredient.index');
    }

    /**
     * @Route("/allIngredients", name="list_of_ingredients", methods="GET")
     */
    public function getIngredientsApi(IngredientRepository $ingredientRepository)
    {
        $ingredients = $ingredientRepository->findAll();
        /* $em = $this->getDoctrine()->getManagerForClass(Ingredient::class);
        $ingredients = $this->indexManager->search('query', Ingredient::class, $em); */
        return new JsonResponse(
            [
                'ingredients' => $ingredients,
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
