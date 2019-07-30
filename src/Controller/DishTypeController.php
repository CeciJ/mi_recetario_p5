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
        $form = $this->createForm(DishTypeType::class, $dishType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dishType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.dish_type.index');
        }

        return $this->render('admin/dish_type/new.html.twig', [
            'dish_type' => $dishType,
            'form' => $form->createView(),
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
        $form = $this->createForm(DishTypeType::class, $dishType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.dish_type.index', [
                'id' => $dishType->getId(),
            ]);
        }

        return $this->render('admin/dish_type/edit.html.twig', [
            'dish_type' => $dishType,
            'form' => $form->createView(),
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

        return $this->redirectToRoute('admin.dish_type.index');
    }
}
