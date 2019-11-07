<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class IngredientType extends AbstractType
{
    private $router;

    private $ingredients;

    public function __construct(IngredientRepository $ingredientRepository, RouterInterface $router)
    {
        $this->router = $router;

        //$this->ingredients = $ingredientRepository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('name')
            ->add('name', null, [
                'label' => false,
            ])
            /* ->add('name', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'choices'  => $this->ingredients,
                'choice_label' => function(Ingredient $ingredient, $key, $value) {
                    return strtoupper($ingredient->getName());
                },
            ]) */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
            'attr' => [
                'class' => 'js-user-autocomplete',
                //'data-autocomplete-url' => $this->router->generate('list_of_ingredients')
            ]
        ]);
    }
}
