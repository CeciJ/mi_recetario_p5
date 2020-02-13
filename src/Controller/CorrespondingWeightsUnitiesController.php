<?php

namespace App\Controller;

use App\Entity\CorrespondingWeightsUnities;
use App\Form\CorrespondingWeightsUnitiesType;
use App\Repository\CorrespondingWeightsUnitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/corresponding")
 */
class CorrespondingWeightsUnitiesController extends AbstractController
{
    /**
     * @Route("/", name="admin.corresponding.index", methods={"GET"})
     */
    public function index(CorrespondingWeightsUnitiesRepository $correspondingWeightsUnitiesRepository): Response
    {
        return $this->render('corresponding_weights_unities/index.html.twig', [
            'corresponding_weights_unities' => $correspondingWeightsUnitiesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.corresponding.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $correspondingWeightsUnity = new CorrespondingWeightsUnities();
        $form = $this->createForm(CorrespondingWeightsUnitiesType::class, $correspondingWeightsUnity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($correspondingWeightsUnity);
            $entityManager->flush();

            return $this->redirectToRoute('admin.corresponding.index');
        }

        return $this->render('corresponding_weights_unities/new.html.twig', [
            'corresponding_weights_unity' => $correspondingWeightsUnity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.corresponding.show", methods={"GET"})
     */
    public function show(CorrespondingWeightsUnities $correspondingWeightsUnity): Response
    {
        return $this->render('corresponding_weights_unities/show.html.twig', [
            'corresponding_weights_unity' => $correspondingWeightsUnity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.corresponding.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CorrespondingWeightsUnities $correspondingWeightsUnity): Response
    {
        $form = $this->createForm(CorrespondingWeightsUnitiesType::class, $correspondingWeightsUnity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.corresponding.index');
        }

        return $this->render('corresponding_weights_unities/edit.html.twig', [
            'corresponding_weights_unity' => $correspondingWeightsUnity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.corresponding.delete", methods={"DELETE"})
     */
    public function delete(Request $request, CorrespondingWeightsUnities $correspondingWeightsUnity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$correspondingWeightsUnity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($correspondingWeightsUnity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.corresponding.index');
    }
}
