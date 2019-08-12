<?php

namespace App\Controller;

use DateTime;
use App\Entity\Recipe;
use App\Entity\ListSearch;
use App\Entity\MealPlanning;
use App\Form\ListSearchType;
use App\Form\MealPlanningType;
//use Symfony\Component\Validator\Constraints\DateTime;
use Doctrine\Common\Util\Debug;
use App\Repository\RecipeRepository;
use App\Repository\MealPlanningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\RecipeIngredientsRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/meal_planning")
 */
class MealPlanningController extends AbstractController
{

    /**
     * @Route("/", name="meal_planning.index", methods={"GET"})
     */
    public function index(MealPlanningRepository $mealPlanningRepository, RecipeIngredientsRepository $recipeIngRepository, Request $request): Response
    {
        $search = new ListSearch();
        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);

        $mealPlannings = $mealPlanningRepository->findAllQuery($search);
        //dump($mealPlannings);

        $allIngredients = [];
        //$allRecipes = [];

        foreach($mealPlannings as $meal){
            $recipe = $meal->getRecipe();
            $ingredients = $recipe->getRecipeIngredients();
            $allIngredients[] = $ingredients;
        }
        
        $tabNames = [];
        foreach($allIngredients as $ingredients){
            foreach($ingredients as $ingredient){
                $name = $ingredient->getNameIngredient();
                if(!in_array($name, $tabNames)){
                    $tabNames[] = $name;
                } 
            }
        }
        //dump($tabNames);

        $listIngredients = $recipeIngRepository->compileIngredients($search, $tabNames, $allIngredients);
        dump($listIngredients);

        /*
        $listIngredients = $recipeIngRepository->findByIngredientName($tabNames);
        dump($listIngredients);
        */
        //dump($allIngredients);

        return $this->render("meal_planning/index.html.twig", [
            'current_menu' => 'recipes',
            'meal_plannings' => $mealPlannings,
            'form' => $form->createView(),
            'allIngredients'=> $allIngredients,   
            'listIngredients' => $listIngredients   
        ]);
       
    }

    /**
     * @Route("/calendar", name="meal_planning.calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('meal_planning/calendar.html.twig');
    }
    
    /**
     * @Route("/new", name="meal_planning.new", methods={"GET","POST"})
     */
    public function new(Request $request, RecipeRepository $recipeRepo): Response
    {
        
        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $data = urldecode($data);
            $datas = explode('&', $data);
            $dataStart = $datas[0];
            $datastart = explode('=', $dataStart);
            $dataStart = $datastart[1];
            $dataStart = explode('(', $dataStart);
            $dateStart = trim($dataStart[0]);
            $start = new DateTime($dateStart);

            $dataTitle = $datas[1];
            $datatitle = explode('=', $dataTitle);
            $dataTitle = $datatitle[1];

            //exit(\Doctrine\Common\Util\Debug::dump($dataTitle));

            /*
            if($detailOrigin == 'dateClick') {

            } else {

            }
            exit(\Doctrine\Common\Util\Debug::dump($detailOrigin));
            */
            /*
            if(strlen($dataTitle) < 5)
            {
                $dataTitle = (int) $dataTitle;
                $recipe = $recipeRepo->find($dataTitle);
                $recipeNameOk = $recipe->getname();

                $mealPlanning = new MealPlanning();
                $mealPlanning->setTitle($recipeNameOk);
                $mealPlanning->setBeginAt($start);
                //$mealPlanning->addRecipesData($recipe);
            }
            else
            {
            */
                $recipe = $recipeRepo->findOneByName($dataTitle);

                $mealPlanning = new MealPlanning();
                $mealPlanning->setTitle($dataTitle);
                $mealPlanning->setBeginAt($start);
                $mealPlanning->setRecipe($recipe);
            //}

            //exit(\Doctrine\Common\Util\Debug::dump($mealPlanning));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlanning);
            $entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'ok',
                ],
                JsonResponse::HTTP_CREATED
            );
        }
        /*
        else {
            $mealPlanning = new MealPlanning();

            $form = $this->createForm(MealPlanningType::class, $mealPlanning);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($mealPlanning);
                $entityManager->flush();

                return $this->redirectToRoute('meal_planning.index');
            }
        }
        */
        /*
        $mealPlanning = new MealPlanning();

            $form = $this->createForm(MealPlanningType::class, $mealPlanning);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($mealPlanning);
                $entityManager->flush();

                return $this->redirectToRoute('meal_planning.index');
            }
        
        return $this->render('pages/home.html.twig', [
            'meal_planning' => $mealPlanning,
            'form' => $form->createView(),
        ]);
        */
    }

    /**
     * @Route("/{id}", name="meal_planning.show", methods={"GET"})
     */
    public function show(MealPlanning $mealPlanning, Request $request): Response
    {
        $recipe = $mealPlanning->getRecipe();
        
        return $this->render('meal_planning/show.html.twig', [
            'meal_planning' => $mealPlanning,
            'recipe' => $recipe
        ]);
    }

    /**
     * @Route("/edit/{id}", name="meal_planning.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MealPlanning $mealPlanning): Response
    {
        $form = $this->createForm(MealPlanningType::class, $mealPlanning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meal_planning.index', [
                'id' => $mealPlanning->getId(),
            ]);
        }

        return $this->render('meal_planning/edit.html.twig', [
            'meal_planning' => $mealPlanning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meal_planning.delete", methods={"DELETE"})
     */
    public function delete(Request $request, MealPlanning $mealPlanning): Response
    {

        if ($this->isCsrfTokenValid('delete'.$mealPlanning->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mealPlanning);
            $entityManager->flush();
        }

        return $this->redirectToRoute('meal_planning.index');
    }

    /**
     * @Route("/deletedragnout/{id}", name="meal_planning.deleteDragnOut", methods={"DELETE"})
     */
    public function deleteDragnOut(Request $request, MealPlanning $mealPlanning): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mealPlanning);
            $entityManager->flush();
            //exit(\Doctrine\Common\Util\Debug::dump($data));
        }

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
        //return $this->redirectToRoute('home');
    }

}
