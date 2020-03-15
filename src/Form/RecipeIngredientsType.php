<?php

namespace App\Form;

use App\Form\IngredientType;
use App\Entity\RecipeIngredients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecipeIngredientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', null, ['attr' => ['class' => 'qualityIngredient']])
            ->add('unit', MeasureUnitType::class, [
                'attr' => ['class' => 'qualityIngredient'],
                'label' => 'Unité (ex. kg)'
            ])
            ->add('nameIngredient', IngredientType::class, ['attr' => ['class' => 'qualityIngredient']])
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
