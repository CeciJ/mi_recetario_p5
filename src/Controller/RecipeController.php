<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Entity\ContactMail;
use App\Entity\RecipeSearch;
use App\Form\ContactMailType;
use App\Form\RecipeSearchType;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use App\Notification\ContactMailNotification;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        $search = new RecipeSearch();
        $form = $this->createForm(RecipeSearchType::class, $search);
        $form->handleRequest($request);

        $recipes =  $paginator->paginate(
            $this->repository->findAllQuery($search),
            $request->query->getInt('page', 1),
            12 
        );

        return $this->render("recipe/index.html.twig", [
            'current_menu' => 'recipes',
            'recipes' => $recipes,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/recettes/{slug}-{id}", name="recipe.show", requirements={"slug": "[a-z0-9\-]*"}) 
     * @return Response
     */
    public function show(Recipe $recipe, string $slug, Request $request, ContactMailNotification $notification): Response
    {
        $slugChecked = $recipe->getSlug();

        if($slugChecked != $slug)
        {
            return $this->redirectToRoute("recipe.show", [
                'id' => $recipe->getId(),
                'slug' => $slugChecked
            ], 301);
        }

        $contactMail = new ContactMail();
        $contactMail->setRecipe($recipe);
        $form = $this->createForm(ContactMailType::class, $contactMail);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $notification->notify($contactMail);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            /*
            return $this->redirectToRoute("recipe.show", [
                'id' => $recipe->getId(),
                'slug' => $slugChecked
            ]);
            */
        }

        return $this->render("recipe/show.html.twig", [
            'current_menu' => 'recipes',
            'recipe' => $recipe,
            'form' => $form->createView()
        ]);
    }

}