<?php

namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use App\Entity\ListSearch;
use App\Entity\MealPlanning;
use App\Form\ListSearchType;
use App\Repository\RecipeRepository;
use App\Repository\MealPlanningRepository;
use App\Helpers\ConverterHelper;
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
     * @Route("/savetopdf/{startDate}/{endDate}/{listText}", name="meal_planning.saveToPdf", methods={"GET","POST"})
     */
    public function saveToPdf($startDate, $endDate, $listText, MealPlanningRepository $mealPlanningRepository)
    {
        $search = new ListSearch();
        $startDay = new DateTime($startDate);
        $startDatePeriod = $search->setStartPeriod($startDay);
        $endDay = new DateTime($endDate);
        $endDatePeriod = $search->setEndPeriod($endDay);

        $finalIngredients = explode(',', $listText);
        $mealPlannings = $mealPlanningRepository->findAllQuery($search);

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->set_option('isPhpEnabled', true);
        $dompdf->set_option('isRemoteEnabled', true);
        $dompdf->set_option('isHtml5ParserEnabled', true);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView("print/mypdf.html.twig", [
            'title' => "Welcome to our PDF Test",   
            'meal_plannings' => $mealPlannings,
            'finalIngredients' => $finalIngredients
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/sendbymail/{startDate}/{endDate}/{listText}", name="meal_planning.sendByMail", methods={"GET","POST"})
     */
    public function sendByMail($startDate, $endDate, $listText, MealPlanningRepository $mealPlanningRepository, RecipeIngredientsRepository $recipeIngRepository, Request $request, \Swift_Mailer $mailer, Environment $renderer, ConverterHelper $converter)
    {
        $search = new ListSearch();
        $startDay = new DateTime($startDate);
        $startDatePeriod = $search->setStartPeriod($startDay);
        $endDay = new DateTime($endDate);
        $endDatePeriod = $search->setEndPeriod($endDay);

        $finalIngredients = explode(',', $listText);

        $mealPlannings = $mealPlanningRepository->findAllQuery($search);

        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);

        $finalList = $this->generateList($mealPlanningRepository, $search, $converter);
        $allIngredients = $finalList['finalIngredients'];

        $message = (new \Swift_Message('Liste d\'ingrédients'))
            ->setFrom('cec.jourdan@gmail.com')
            ->setTo('cec.jourdan@gmail.com')
            ->setBody($renderer->render('emails/list.html.twig', [
                'finalIngredients' => $finalIngredients,
                'startDate' => $startDate,
                'endDate' => $endDate 

            ]), 'text/html');
        $mailer->send($message);

        $this->addFlash(
            'success',
            'La liste a bien été envoyée par mail!'
        );

        return $this->render("meal_planning/index.html.twig", [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'current_menu' => 'recipes',
            'meal_plannings' => $mealPlannings,
            'form' => $form->createView(),
            'finalIngredients' => $allIngredients,
            'listText' => 'list'
        ]);
    }

    /**
     * @Route("/", name="meal_planning.index", methods={"GET"})
     */
    public function index(MealPlanningRepository $mealPlanningRepository, RecipeIngredientsRepository $recipeIngRepository, Request $request,  ConverterHelper $converter): Response
    {
        $search = new ListSearch();
        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);

        if($form->isSubmitted()){
            $finalList = $this->generateList($mealPlanningRepository, $search, $converter);
            $finalIngredients = $finalList['finalIngredients'];
            $mealPlannings = $finalList['mealPlannings'];

            return $this->render("meal_planning/index.html.twig", [
                'current_menu' => 'recipes',
                'form' => $form->createView(),
                'finalIngredients' => $finalIngredients,
                'meal_plannings' => $mealPlannings,
                'startDate' => $search->getStartPeriod(),
                'endDate' => $search->getEndPeriod(),
                'listText' => 'list'
            ]);

        }

        return $this->render("meal_planning/index.html.twig", [
            'current_menu' => 'recipes',
            'form' => $form->createView()
        ]);
       
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

            $recipe = $recipeRepo->findOneByName($dataTitle);

            $mealPlanning = new MealPlanning();
            $mealPlanning->setTitle($dataTitle);
            $mealPlanning->setBeginAt($start);
            $mealPlanning->setRecipe($recipe);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlanning);
            $entityManager->flush();
            $id = $mealPlanning->getId();

            return new JsonResponse(
                [
                    'status' => 'ok',
                    'meal_planning' => $mealPlanning,
                    'id' => $id,
                    'begin_at' => $start
                ],
                JsonResponse::HTTP_CREATED
            );
        }
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
    public function edit(Request $request, MealPlanningRepository $mealPlanningRepo): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $data = urldecode($data);
            $datas = explode('&', $data);
            $dataForId = explode('=', $datas[2]);
            $mealPlanningId = $dataForId[1];
            $dataForNewDate = explode('=', $datas[0]);
            $newDate = $dataForNewDate[1];
            $date = explode('(', $newDate);
            $date = new DateTime($date[0]);

            $mealPlanningToEdit = $mealPlanningRepo->find($mealPlanningId);
            $mealPlanningToEdit->setBeginAt($date);
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlanningToEdit);
            $entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'ok',
                ],
                JsonResponse::HTTP_CREATED
            );
        }
    }

    /**
     * @Route("/delete/{id}", name="meal_planning.delete", methods={"DELETE"})
     */
    public function delete(Request $request, MealPlanning $mealPlanning): Response
    {
        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mealPlanning);
            $entityManager->flush();
        }

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
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
        }

        return new JsonResponse(
            [
                'status' => 'ok',
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    private function generateList(MealPlanningRepository $mealPlanningRepository, ListSearch $search,  ConverterHelper $converter)
    {
        $mealPlannings = $mealPlanningRepository->findAllQuery($search);
        $allIngredients = [];
        foreach($mealPlannings as $meal){
            $recipe = $meal->getRecipe();
            $ingredients = $recipe->getRecipeIngredients();
            foreach($ingredients as $ingredient){
                $allIngredients[] = $ingredient;
            }
        }

        $finalIngredients = [];
        foreach($allIngredients as $ingredient){
            $convertedIngredient = $converter->unifyUnity($ingredient);
            $name = $convertedIngredient->getNameIngredient()->getName();
            if(array_key_exists($name, $finalIngredients)){
                if($convertedIngredient->getUnit() == $finalIngredients[$name]['unit']){
                    $finalIngredients[$name]['quantity'] = $finalIngredients[$name]['quantity'] + $convertedIngredient->getQuantity();
                } else {
                    $finalIngredients[$name.$convertedIngredient->getId()] = [
                        'quantity' => $convertedIngredient->getQuantity(),
                        'unit' => $convertedIngredient->getUnit()
                    ];
                }
            } else {
                $finalIngredients[$name] = [
                    'quantity' => $convertedIngredient->getQuantity(),
                    'unit' => $convertedIngredient->getUnit()
                ];
            }
        }

        ksort($finalIngredients);

        $finalList = [];
        $finalList['mealPlannings'] = $mealPlannings;
        $finalList['finalIngredients'] = $finalIngredients; 

        return $finalList;
    }

}
