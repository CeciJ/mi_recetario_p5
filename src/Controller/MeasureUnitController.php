<?php

namespace App\Controller;

use App\Entity\MeasureUnit;
use App\Form\MeasureUnitType;
use App\Repository\MeasureUnitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/measure_unit")
 */
class MeasureUnitController extends AbstractController
{
    /**
     * @Route("/", name="admin.measure_unit.index", methods={"GET"})
     */
    public function index(MeasureUnitRepository $measureUnitRepository): Response
    {
        return $this->render('measure_unit/index.html.twig', [
            'measure_units' => $measureUnitRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.measure_unit.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $measureUnit = new MeasureUnit();
        $form = $this->createForm(MeasureUnitType::class, $measureUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($measureUnit);
            $entityManager->flush();

            return $this->redirectToRoute('admin.measure_unit.index');
        }

        return $this->render('measure_unit/new.html.twig', [
            'measure_unit' => $measureUnit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.measure_unit.show", methods={"GET"})
     */
    public function show(MeasureUnit $measureUnit): Response
    {
        return $this->render('measure_unit/show.html.twig', [
            'measure_unit' => $measureUnit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.measure_unit.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MeasureUnit $measureUnit): Response
    {
        $form = $this->createForm(MeasureUnitType::class, $measureUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.measure_unit.index', [
                'id' => $measureUnit->getId(),
            ]);
        }

        return $this->render('measure_unit/edit.html.twig', [
            'measure_unit' => $measureUnit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.measure_unit.delete", methods={"DELETE"})
     */
    public function delete(Request $request, MeasureUnit $measureUnit): Response
    {
        if ($this->isCsrfTokenValid('delete'.$measureUnit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($measureUnit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.measure_unit.index');
    }
}
