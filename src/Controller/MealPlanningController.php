<?php

namespace App\Controller;

use App\Entity\MealPlanning;
use App\Form\MealPlanningType;
use App\Repository\MealPlanningRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/meal_planning")
 */
class MealPlanningController extends AbstractController
{

    /**
     * @Route("/", name="meal_planning.index", methods={"GET"})
     */
    public function index(MealPlanningRepository $mealPlanningRepository): Response
    {
        return $this->render('meal_planning/index.html.twig', [
            'meal_plannings' => $mealPlanningRepository->findAll(),
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
    public function new(Request $request): Response
    {
        $mealPlanning = new MealPlanning();
        $form = $this->createForm(MealPlanningType::class, $mealPlanning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlanning);
            $entityManager->flush();

            return $this->redirectToRoute('meal_planning.index');
        }

        return $this->render('meal_planning/new.html.twig', [
            'meal_planning' => $mealPlanning,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="meal_planning.show", methods={"GET"})
     */
    public function show(MealPlanning $mealPlanning): Response
    {
        return $this->render('meal_planning/show.html.twig', [
            'meal_planning' => $mealPlanning,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="meal_planning.edit", methods={"GET","POST"})
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

}
