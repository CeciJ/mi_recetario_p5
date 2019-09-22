<?php

namespace App\Controller;

use App\Entity\Option;
use App\Form\OptionType;
use App\Repository\OptionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/option")
 */
class OptionController extends AbstractController
{
    /**
     * @Route("/", name="admin.option.index", methods={"GET"})
     */
    public function index(OptionRepository $optionRepository): Response
    {
        return $this->render('admin/option/index.html.twig', [
            'options' => $optionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin.option.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $option = new Option();
        $formAddOption = $this->createForm(OptionType::class, $option);
        $formAddOption->handleRequest($request);

        if ($formAddOption->isSubmitted() && $formAddOption->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/option/new.html.twig', [
            'formAddOption' => $formAddOption->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin.option.show", methods={"GET"})
     */
    public function show(Option $option): Response
    {
        return $this->render('admin/option/show.html.twig', [
            'option' => $option,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.option.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Option $option, OptionRepository $optionRepo): Response
    {
        /* $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin.option.index', [
                'id' => $option->getId(),
            ]);
        }

        return $this->render('admin/option/edit.html.twig', [
            'option' => $option,
            'form' => $form->createView(),
        ]); */
        $formEditOption = $this->createForm(OptionType::class, $option);
        $formEditOption->handleRequest($request);

        if($request->isXmlHttpRequest()) {
            $data = $request->getContent();
            $dataArray = explode('&', $data);
            $idArray = explode('=', $dataArray[0]);
            $id = $idArray[1];
            $newNameArray = explode('=', $dataArray[1]);
            $newName = $newNameArray[1];
            $newName = urldecode($newName);

            $option = $optionRepo->find($id);
            $option->setName($newName);

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

        /* if ($formEditOption->isSubmitted() && $formEditOption->isValid()) {
            dump($request); die;
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        } */

        return $this->render('admin/option/edit.html.twig', [
            'formEditOption' => $formEditOption->createView(),
            'option' => $option
        ]);
    }

    /**
     * @Route("/{id}", name="admin.option.delete", methods={"DELETE"})
     */
    public function delete(Request $request, Option $option): Response
    {
        if ($this->isCsrfTokenValid('delete'.$option->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($option);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.all_options');
    }
}
