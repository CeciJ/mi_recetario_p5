<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RecipeController extends AbstractController 
{

    private $repository;

    private $em;

    public function __construct(RecipeRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/recettes", name="recipe.index") 
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $recipes =  $paginator->paginate(
            $this->repository->findAllQuery(),
            $request->query->getInt('page', 1),
            12 
        );
        return $this->render("recipe/index.html.twig", [
            'current_menu' => 'recipes',
            'recipes' => $recipes
        ]);
    }

    /**
     * @Route("/recettes/{slug}-{id}", name="recipe.show", requirements={"slug": "[a-z0-9\-]*"}) 
     * @return Response
     */
    public function show(Recipe $recipe, string $slug): Response
    {
        $slugChecked = $recipe->getSlug();

        if($slugChecked != $slug)
        {
            return $this->redirectToRoute("recipe.show", [
                'id' => $recipe->getId(),
                'slug' => $slugChecked
            ], 301);
        }

        return $this->render("recipe/show.html.twig", [
            'current_menu' => 'recipes',
            'recipe' => $recipe
        ]);
    }

}