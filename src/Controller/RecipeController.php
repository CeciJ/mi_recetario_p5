<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController {

    /**
     * @Route("/recettes", name="recipe.index") 
     * @return Response
     */
    public function index(): Response
    {
        return $this->render("recipe/index.html.twig", [
            'current_menu' => 'recipes'
        ]);
    }

}