<?php

namespace App\Controller;

use App\Entity\DishType;
use App\Form\DishTypeType;
use App\Repository\DishTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dishtype")
 */
class DishTypeController extends AbstractController
{
    /**
     * @Route("/", name="admin.dish_type.index", methods={"GET"})
     */
    public function index(DishTypeRepository $dishTypeRepository): Response
    {
        return $this->render('admin/dish_type/index.html.twig', [
            'dish_types' => $dishTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.dish_type.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $dishType = new DishType();
        $formDishType = $this->createForm(DishTypeType::class, $dishType);
        $formDishType->handleRequest($request);

        if ($formDishType->isSubmitted() && $formDishType->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dishType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/dish_type/_form.html.twig', [
            'dish_type' => $dishType,
            'formDishType' => $formDishType->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dish_type.show", methods={"GET"})
     */
    public function show(DishType $dishType): Response
    {
        return $this->render('admin/dish_type/show.html.twig', [
            'dish_type' => $dishType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.dish_type.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DishType $dishType): Response
    {
        $formDishType = $this->createForm(DishTypeType::class, $dishType);
        $formDishType->handleRequest($request);

        if ($formDishType->isSubmitted() && $formDishType->isValid()) {
            dump('test');
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/dish_type/_form.html.twig', [
            'dish_type' => $dishType,
            'formDishType' => $formDishType->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.dish_type.delete", methods={"DELETE"})
     */
    public function delete(Request $request, DishType $dishType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dishType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($dishType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.all_options');
    }
}
