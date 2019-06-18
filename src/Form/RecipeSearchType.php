<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\DishType;
use App\Entity\FoodType;
use App\Entity\RecipeSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class RecipeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxTotalTime', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Temps de préparation maximum'
                ]
            ])
            ->add('numberPersons', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Nombre de personnes'
                ]
            ])
            ->add('DishTypes', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => DishType::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('foodTypes', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => FoodType::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('options', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RecipeSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
