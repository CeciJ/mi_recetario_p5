<?php

namespace App\Controller;

use App\Entity\BuyList;
use App\Form\BuyListType;
use App\Entity\ListSearch;
use App\Form\ListSearchType;
use App\Repository\BuyListRepository;
use App\Repository\MealPlanningRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/buyList")
 */
class BuyListController extends AbstractController
{
    
    /**
     * @Route("/", name="buy_list.index", methods={"GET"})
     */
    public function index(BuyListRepository $buyListRepository, MealPlanningRepository $mealPlanningRepository, Request $request): Response
    {

        $search = new ListSearch();
        $form = $this->createForm(ListSearchType::class, $search);
        $form->handleRequest($request);

        $list =  $mealPlanningRepository->findAllQuery($search);

        return $this->render("buy_list/index.html.twig", [
            'current_menu' => 'recipes',
            'buy_lists' => $list,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="buy_list.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $buyList = new BuyList();
        $form = $this->createForm(BuyListType::class, $buyList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($buyList);
            $entityManager->flush();

            return $this->redirectToRoute('buy_list.index');
        }

        return $this->render('buy_list/new.html.twig', [
            'buy_list' => $buyList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="buy_list.show", methods={"GET"})
     */
    public function show(BuyList $buyList): Response
    {
        return $this->render('buy_list/show.html.twig', [
            'buy_list' => $buyList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="buy_list.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BuyList $buyList): Response
    {
        $form = $this->createForm(BuyListType::class, $buyList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('buy_list.index', [
                'id' => $buyList->getId(),
            ]);
        }

        return $this->render('buy_list/edit.html.twig', [
            'buy_list' => $buyList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="buy_list.delete", methods={"DELETE"})
     */
    public function delete(Request $request, BuyList $buyList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$buyList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($buyList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('buy_list.index');
    }
}
