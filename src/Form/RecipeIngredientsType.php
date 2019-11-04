<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Entity\RecipeIngredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeIngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('unit', MeasureUnitType::class)
            /* ->add('unit', CollectionType::class, [
                'entry_type' => MeasureUnitType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ]) */
            ->add('nameIngredient', IngredientType::class)
            /* ->add('nameIngredient', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ])  */
            /* ->add('nameIngredient', TextType::class, [
                'attr' => [
                    'autocomplete' => 'on',
                    'class' => 'js-user-autocomplete'
                ]
            ]) */
            /* ->add('nameIngredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => false,
            ]) */
            /* ->add('nameIngredient', CollectionType::class, [
                'entry_type' => IngredientType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
            ]) */
            //->add('nameIngredient')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeIngredients::class,
            'csrf_protection' => false,
            'translation_domain' => 'forms',
        ]);
    }
}
