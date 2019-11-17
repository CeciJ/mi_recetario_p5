<?php

namespace App\Controller;

use DateTime;
use DateInterval;
use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use App\Entity\ListSearch;
use App\Entity\MealPlanning;
use App\Form\ListSearchType;
use App\Form\MealPlanningType;
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
     * @Route("/savetopdf/{startDate}/{endDate}/{listText}", name="meal_planning.saveToPdf", methods={"GET","POST"})
     */
    public function saveToPdf($startDate, $endDate, $listText, MealPlanningRepository $mealPlanningRepository)
    {
        $search = new ListSearch();
        $startDay = new DateTime($startDate);
        $startDatePeriod = $search->setStartPeriod($startDay);
        //$startDate = $startDatePeriod->format('Y-m-d');
        $endDay = new DateTime($endDate);
        $endDatePeriod = $search->setEndPeriod($endDay);
        //$endDate = $endDatePeriod->format('Y-m-d');

        $finalIngredients = explode(',', $listText);
        $mealPlannings = $mealPlanningRepository->findAllQuery($search);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
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
    public function sendByMail($startDate, $endDate, $listText, MealPlanningRepository $mealPlanningRepository, RecipeIngredientsRepository $recipeIngRepository, Request $request, \Swift_Mailer $mailer, Environment $renderer)
    {
        $search = new ListSearch();
        $startDay = new DateTime($startDate);
        $startDatePeriod = $search->setStartPeriod($startDay);
        //$startDate = $startDatePeriod->format('Y-m-d');
        $endDay = new DateTime($endDate);
        $endDatePeriod = $search->setEndPeriod($endDay);
        //$endDate = $endDatePeriod->format('Y-m-d');

        $finalIngredients = explode(',', $listText);

        $mealPlannings = $mealPlanningRepository->findAllQuery($search);

        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);

        $finalList = $this->generateList($mealPlanningRepository, $search);
        $allIngredients = $finalList['finalIngredients'];

        $message = (new \Swift_Message('Liste d\'ingrédients'))
            ->setFrom('cec.jourdan@gmail.com')
            ->setTo('cec.jourdan@gmail.com')
            //->setReplyTo($contactMail->getEmail())
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

        //return json
        //$this->addFlash('success', 'Recette ajoutée avec succès');
        //return($this->redirectToRoute('meal_planning.index'));

        return $this->render("meal_planning/index.html.twig", [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'current_menu' => 'recipes',
            'meal_plannings' => $mealPlannings,
            'form' => $form->createView(),
            'finalIngredients' => $allIngredients,
            'listText' => 'list'
            //'display' => 'list', 
        ]);
    }

    /**
     * @Route("/", name="meal_planning.index", methods={"GET"})
     */
    public function index(MealPlanningRepository $mealPlanningRepository, RecipeIngredientsRepository $recipeIngRepository, Request $request): Response
    {
        $search = new ListSearch();

        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);
        //$startDate = new DateTime('now');
        //$endDate = new DateTime('now');
        //$endDate = $endDate->add(new DateInterval('PD7'));

        if($form->isSubmitted()){
            $finalList = $this->generateList($mealPlanningRepository, $search);
            $finalIngredients = $finalList['finalIngredients'];
            $mealPlannings = $finalList['mealPlannings'];
            //dump($finalIngredients);

            return $this->render("meal_planning/index.html.twig", [
                'current_menu' => 'recipes',
                'form' => $form->createView(),
                //'messageOK' => 'pour l instant c est bon',
                'finalIngredients' => $finalIngredients,
                'meal_plannings' => $mealPlannings
            ]);

        }

        dump('sans form');

        /* if($form->isSubmitted()){
            $startDate = $search->getStartPeriod();
            //$startDate = $startDate->format('Y-m-d H:i:s');
            $endDate = $search->getEndPeriod()->add(new DateInterval('PT23H59M59S'));
            //$endDate = $endDate->format('Y-m-d H:i:s');
            dump($search); 
            //$mealPlannings = $mealPlanningRepository->findAllQuery($search);
            //dump($mealPlannings);
            $finalList = $this->generateList($mealPlanningRepository, $search);
            dump($finalList); 
            $finalIngredients = $finalList['finalIngredients'];
            $mealPlannings = $finalList['mealPlannings'];
        }  */

        return $this->render("meal_planning/index.html.twig", [
            'current_menu' => 'recipes',
            'form' => $form->createView(),
            /* 'meal_plannings' => $mealPlannings,
            'finalIngredients' => $finalIngredients,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'listText' => 'list' */
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
            //$finalNewDate = $date::createFromFormat($newDate, )

            $mealPlanningToEdit = $mealPlanningRepo->find($mealPlanningId);
            $mealPlanningToEdit->setBeginAt($date);
            
            //dump($mealPlanningToEdit); die();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mealPlanningToEdit);
            $entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'ok',
                ],
                JsonResponse::HTTP_CREATED
            );
            //exit(\Doctrine\Common\Util\Debug::dump($mealPlanningToEdit));
        }
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

    private function generateList(MealPlanningRepository $mealPlanningRepository, ListSearch $search)
    {
        //dump($search);
        
        $mealPlannings = $mealPlanningRepository->findAllQuery($search);

        $allIngredients = [];
        foreach($mealPlannings as $meal){
            $recipe = $meal->getRecipe();
            $ingredients = $recipe->getRecipeIngredients();
            $allIngredients[] = $ingredients;
        }
        
        $tabNames = [];
        $tabUnique = [];
        $finalIngredients = [];
        foreach($allIngredients as $ingredients){

            foreach($ingredients as $ingredient){
                $name = $ingredient->getNameIngredient();
                $quantity = $ingredient->getQuantity();
                $unit = $ingredient->getUnit();

                // Si l'ingrédient n'existe pas encore, on le stocke dans le tableau
                if(!in_array($name, $tabNames)){
                    $tabNames[] = $name;
                    $tabUnique['name'] = $name;
                    $tabUnique['quantity'] = $quantity;
                    $tabUnique['unit'] = $unit;
                    $finalIngredients[] = $tabUnique;
                } 
                else {
                    // Sinon on regarde l'unité, si c'est la même on ajoute la quantité
                    foreach($finalIngredients as $key => $finalIngredient){
                        if($name == $finalIngredient['name']){
                            if($unit == $finalIngredient['unit']){
                                $finalIngredient['quantity'] = $finalIngredient['quantity'] + $quantity;
                                $finalIngredients[] = $finalIngredient;
                            }
                        }
                    }
                }
            }
        }

        foreach ($finalIngredients as $key => $value) {
            $name2[$key] = $value['name'];
            $quantity2[$key] = $value['quantity'];
        }
        array_multisort($name2, SORT_ASC, SORT_STRING, $finalIngredients);

        $length = count($finalIngredients);

        $tabNames2 = [];
        for($i = 0; $i < $length; $i++){
            if(!in_array($finalIngredients[$i]['name'], $tabNames2)){
                $tabNames2[] = $finalIngredients[$i]['name'];
            } else {
                unset($finalIngredients[$i - 1]);
            }
        }

        $finalList = [];
        $finalList['mealPlannings'] = $mealPlannings;
        $finalList['finalIngredients'] = $finalIngredients;

        return $finalList;
    }

}
