<?php

namespace App\Form;

use App\Entity\MealPlanning;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class MealPlanningType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('beginAt', DateTimeType::class, [
                'format' => 'yyyy-MM-dd\'T\'HH:mm:ssZ'
            ])
            ->add('endAt', DateTimeType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MealPlanning::class,
            'csrf_protection' => false,
        ]);
    }
}
