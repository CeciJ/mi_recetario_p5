<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\CorrespondingWeightsUnities;
use App\Form\CorrespondingWeightsUnitiesType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CorrespondingWeightsUnitiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/corresponding")
 */
class CorrespondingWeightsUnitiesController extends AbstractController
{
    /**
     * @Route("/", name="admin.corresponding.index", methods={"GET", "POST"})
     */
    public function index(Request $request, IngredientController $ingController, CorrespondingWeightsUnitiesRepository $correspondingWeightsUnitiesRepository): Response
    {
        $correspondingWeightsUnity = new CorrespondingWeightsUnities();
        $form = $this->createForm(CorrespondingWeightsUnitiesType::class, $correspondingWeightsUnity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientName = $form->getData()->getIngredient();
            $repository = $this->getDoctrine()->getRepository(Ingredient::class);
            $ingredientToCheck = $repository->findOneBy(['name' => $ingredientName]);
            $ingredient = new Ingredient;
            if(!$ingredientToCheck){
                $ingredient->setName($ingredientName);
                $entityManager = $ingController->getDoctrine()->getManager();
                $entityManager->persist($ingredient); 
                $ingredient->setWeight($correspondingWeightsUnity);
            } else {
                $ingredientToCheck->setWeight($correspondingWeightsUnity);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($correspondingWeightsUnity);
            $entityManager->flush();

            return $this->redirectToRoute('admin.corresponding.index');
        }

        return $this->render('corresponding_weights_unities/index.html.twig', [
            'corresponding_weights_unities' => $correspondingWeightsUnitiesRepository->findAll(),
            'form' => $form->createView(),
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
