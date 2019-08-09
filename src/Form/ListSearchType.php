<?php

namespace App\Form;

use App\Entity\ListSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ListSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startPeriod', DateTimeType::class, [
                'required' => false,
                'label' => 'Du',
                'attr' => [
                    'placeholder' => 'Du'
                ]
            ])
            ->add('endPeriod', DateTimeType::class, [
                'required' => false,
                'label' => 'Au',
                'attr' => [
                    'placeholder' => 'AU'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ListSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
