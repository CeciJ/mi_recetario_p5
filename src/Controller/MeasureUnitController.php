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
        return $this->render('admin/measure_unit/index.html.twig', [
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

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/measure_unit/new.html.twig', [
            'measure_unit' => $measureUnit,
            'formAddMeasureUnit' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.measure_unit.show", methods={"GET"})
     */
    public function show(MeasureUnit $measureUnit): Response
    {
        return $this->render('admin/measure_unit/show.html.twig', [
            'measure_unit' => $measureUnit,
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
            $measureUnit->setName($newName);

            //exit(\Doctrine\Common\Util\Debug::dump($dishType));
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


        /* $form = $this->createForm(MeasureUnitType::class, $measureUnit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.measure_unit.index', [
                'id' => $measureUnit->getId(),
            ]);
        }

        return $this->render('admin/measure_unit/edit.html.twig', [
            'measure_unit' => $measureUnit,
            'formEditMeasureUnit' => $form->createView(),
        ]); */
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
