<?php

namespace App\Controller;

use App\Entity\DishType;
use App\Form\DishTypeType;
use Doctrine\Common\Util\Debug;
use App\Repository\DishTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


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
        $formAddDishType = $this->createForm(DishTypeType::class, $dishType);
        $formAddDishType->handleRequest($request);

        if ($formAddDishType->isSubmitted() && $formAddDishType->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dishType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/dish_type/new.html.twig', [
            'formAddDishType' => $formAddDishType->createView(),
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
     * @Route("/{id}/edit", name="admin.dish_type.edit", methods={"GET","POST"}, options={"expose"=true})
     */
    public function edit(Request $request, DishType $dishType, DishTypeRepository $dishTypeRepo): Response
    {
        $formEditDishType = $this->createForm(DishTypeType::class, $dishType);
        $formEditDishType->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $dataArray = explode('&', $data);
            $idArray = explode('=', $dataArray[0]);
            $id = $idArray[1];
            $newNameArray = explode('=', $dataArray[1]);
            $newName = $newNameArray[1];
            $newName = urldecode($newName);

            $dishType = $dishTypeRepo->find($id);
            $dishType->setName($newName);

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

        /* if ($formEditDishType->isSubmitted() && $formEditDishType->isValid()) {
            dump($request); die;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin.allOptions');
        } */

        return $this->render('admin/dish_type/edit.html.twig', [
            'formEditDishType' => $formEditDishType->createView(),
            'dishType' => $dishType
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
