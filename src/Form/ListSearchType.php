<?php

namespace App\Form;

use App\Entity\ListSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ListSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startPeriod', DateType::class, [
                'required' => true,
                'label' => 'Du',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'input' => 'datetime',
            ])
            ->add('endPeriod', DateType::class, [
                'required' => false,
                'label' => 'Au',
                'format' => 'dd-MM-yyyy',
                'widget' => 'single_text',
                'input' => 'datetime',
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
