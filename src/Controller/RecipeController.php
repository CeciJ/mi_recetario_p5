<?php

namespace App\Controller;

use App\Entity\Option;
use App\Entity\Recipe;
use App\Entity\DishType;
use App\Entity\FoodType;
use App\Form\OptionType;
use App\Form\RecipeType;
use App\Form\DishTypeType;
use App\Form\FoodTypeType;
use App\Entity\ContactMail;
use App\Entity\MeasureUnit;
use App\Entity\RecipeSearch;
use App\Form\ContactMailType;
use App\Form\MeasureUnitType;
use App\Form\RecipeSearchType;
use Doctrine\Common\Util\Debug;
use App\Entity\RecipeIngredients;
use App\Repository\OptionRepository;
use App\Repository\RecipeRepository;
use App\Repository\DishTypeRepository;
use App\Repository\FoodTypeRepository;
use App\Repository\MeasureUnitRepository;
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

        dump($recipe->getRecipeIngredients());

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

    /**
     * @Route("/admin", name="admin.recipe.index") 
     * @return Response
     */
    public function indexAdmin()
    {
        $recipes = $this->repository->findAll();
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes
        ]);
    }

    /**
     * @Route("/admin/all_options", name="admin.all_options") 
     * @return Response
     */
    public function allOptionsAdmin(Request $request, DishTypeRepository $dishTypeRepository, FoodTypeRepository $foodTypeRepository, OptionRepository $optionRepository, MeasureUnitRepository $measureUnitRepository)
    {
        $dish_types = $dishTypeRepository->findAll();
        $food_types = $foodTypeRepository->findAll();
        $options = $optionRepository->findAll();
        $measure_units = $measureUnitRepository->findAll();

        $dishType = new DishType();
        $formDishType = $this->createForm(DishTypeType::class, $dishType);
        $formDishType->handleRequest($request);

        if ($formDishType->isSubmitted() && $formDishType->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dishType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        $foodType = new FoodType();
        $formFoodType = $this->createForm(FoodTypeType::class, $foodType);
        $formFoodType->handleRequest($request);

        if ($formFoodType->isSubmitted() && $formFoodType->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($foodType);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        $option = new Option();
        $formOption = $this->createForm(OptionType::class, $option);
        $formOption->handleRequest($request);

        if ($formOption->isSubmitted() && $formOption->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($option);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        $measureUnit = new MeasureUnit();
        $formMeasureUnit = $this->createForm(MeasureUnitType::class, $measureUnit);
        $formMeasureUnit->handleRequest($request);

        if ($formMeasureUnit->isSubmitted() && $formMeasureUnit->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($measureUnit);
            $entityManager->flush();

            return $this->redirectToRoute('admin.all_options');
        }

        return $this->render('admin/recipe/allOptions.html.twig', [
            'dish_types' => $dish_types,
            'food_types' => $food_types,
            'options' => $options,
            'measure_units' => $measure_units,
            'option' => $option,
            'formDishType' => $formDishType->createView(),
            'formFoodType' => $formFoodType->createView(),
            'formOption' => $formOption->createView(),
            'formMeasureUnit' => $formMeasureUnit->createView()
        ]);
    }

    /**
     * @Route("/admin/recipe/create", name="admin.recipe.new") 
     * @return Response
     */
    public function newAdmin(Request $request)
    {
        $recipe = new Recipe();

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($recipe);
            $this->em->flush();
            $this->addFlash('success', 'Recette ajoutée avec succès');
            return($this->redirectToRoute('admin.recipe.index'));
        }

        return ($this->render('admin/recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/admin/recipe/{id}", name="admin.recipe.edit", methods="GET|POST") 
     * @return Response
     */
    public function editAdmin(Recipe $recipe, Request $request)
    {   
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            dump('form valid and submitted');
            $this->em->flush();
            $this->addFlash('success', 'Recette modifiée avec succès');
            return($this->redirectToRoute('admin.recipe.index'));
        }

        return ($this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView()
        ]));
    }

    /**
     * @Route("/admin/recipe/{id}", name="admin.recipe.delete", methods="DELETE") 
     * @return Response
     */
    public function deleteAdmin(Recipe $recipe, Request $request)
    {
        if($this->isCsrfTokenValid('delete' . $recipe->getId(), $request->get('_token'))){
            $this->em->remove($recipe);
            $this->em->flush();
            $this->addFlash('success', 'Recette supprimée avec succès');
        }
        return($this->redirectToRoute('admin.recipe.index'));
    }

}