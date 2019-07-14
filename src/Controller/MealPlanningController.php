<?php

namespace App\Controller;

use DateTime;
use DateTimeInterface;
use App\Entity\MealPlanning;
use App\Form\MealPlanningType;
use Doctrine\Common\Util\Debug;
use App\Repository\MealPlanningRepository;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Response;
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
    public function index(MealPlanningRepository $mealPlanningRepository): Response
    {
        dump($mealPlanningRepository->findAll());
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

            $mealPlanning = new MealPlanning();
            $mealPlanning->setTitle($dataTitle);
            $mealPlanning->setBeginAt($start);
            
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
