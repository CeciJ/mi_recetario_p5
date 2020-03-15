<?php

namespace App\Controller;

use App\Entity\FoodType;
use App\Form\FoodTypeType;
use App\Repository\FoodTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("foodtype")
 */
class FoodTypeController extends AbstractController
{
    /**
     * @Route("/new", name="admin.food_type.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $foodType = new FoodType();
        $formAddFoodType = $this->createForm(FoodTypeType::class, $foodType);
        $formAddFoodType->handleRequest($request);

        if ($formAddFoodType->isSubmitted() && $formAddFoodType->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foodType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/food_type/new.html.twig', [
            'formAddFoodType' => $formAddFoodType->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.food_type.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, FoodType $foodType, FoodTypeRepository $foodTypeRepo): Response
    {
        $formEditFoodType = $this->createForm(FoodTypeType::class, $foodType);
        $formEditFoodType->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $dataArray = explode('&', $data);
            $idArray = explode('=', $dataArray[0]);
            $id = $idArray[1];
            $newNameArray = explode('=', $dataArray[1]);
            $newName = $newNameArray[1];
            $newName = urldecode($newName);

            $foodType = $foodTypeRepo->find($id);
            $foodType->setName($newName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return new JsonResponse(
                [
                    'status' => 'ok',
                ],
                JsonResponse::HTTP_CREATED
            );
        }

        return $this->render('admin/food_type/edit.html.twig', [
            'formEditFoodType' => $formEditFoodType->createView(),
            'foodType' => $foodType
        ]);
    }

    /**
     * @Route("/{id}", name="admin.food_type.delete", methods={"DELETE"})
     */
    public function delete(Request $request, FoodType $foodType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$foodType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($foodType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.all_options');
    }
}
