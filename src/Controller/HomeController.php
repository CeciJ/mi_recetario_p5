<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController 
{

    /**
     * @Route("/", name="home")
     * @param RecipeRepository $repository
     * @return Response
     */
    public function index(RecipeRepository $repository): Response
    {
        $recipes = $repository->findLatest();
        return $this->render("pages/home.html.twig", [
            'recipes' => $recipes
        ]);
    }

}