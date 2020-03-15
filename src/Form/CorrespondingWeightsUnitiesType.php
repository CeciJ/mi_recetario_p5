<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\CorrespondingWeightsUnities;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CorrespondingWeightsUnitiesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Ingredient')
            ->add('Weight')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CorrespondingWeightsUnities::class,
            'translation_domain' => 'forms'
        ]);
    }
}
