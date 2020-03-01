<?php

namespace App\Controller;

use App\Entity\MeasureUnit;
use App\Form\MeasureUnitType;
use App\Repository\MeasureUnitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/measure_unit")
 */
class MeasureUnitController extends AbstractController
{
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

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/measure_unit/new.html.twig', [
            'measure_unit' => $measureUnit,
            'formAddMeasureUnit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.measure_unit.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, MeasureUnit $measureUnit, MeasureUnitRepository $measureUnitRepo): Response
    {
        $formEditMeasureUnit = $this->createForm(MeasureUnitType::class, $measureUnit);
        $formEditMeasureUnit->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $dataArray = explode('&', $data);
            $idArray = explode('=', $dataArray[0]);
            $id = $idArray[1];
            $newNameArray = explode('=', $dataArray[1]);
            $newName = $newNameArray[1];
            $newName = urldecode($newName);

            $measureUnit = $measureUnitRepo->find($id);
            $measureUnit->setUnit($newName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'ok',
                ],
                JsonResponse::HTTP_CREATED
            );
        }

        return $this->render('admin/measure_unit/edit.html.twig', [
            'formEditMeasureUnit' => $formEditMeasureUnit->createView(),
            'measure_unit' => $measureUnit
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

        return $this->redirectToRoute('admin.all_options');
    }
}
