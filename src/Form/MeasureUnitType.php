<?php

namespace App\Form;

use App\Entity\MeasureUnit;
use Symfony\Component\Form\AbstractType;
use App\Repository\MeasureUnitRepository;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MeasureUnitType extends AbstractType
{

    public function __construct(MeasureUnitRepository $unitRepository)
    {
        $this->units = $unitRepository->findAllDistinct();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /* ->add('unit', null, [
                'label' => false
            ]) */
            ->add('unit', ChoiceType::class, [
                'label' => false,
                'multiple' => false,
                'choices'  => $this->fillLabels(),
                'choice_label' => function($choice, $key, $value) {
                    return $value;
            }])
        ;
    }

    public function fillLabels()
    {
        $allLabels = [];
        foreach($this->units as $key => $unit){
            $allLabels[] = $unit['unit'];
        }
        sort($allLabels);
        return $allLabels;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeasureUnit::class,
            'translation_domain' => 'forms'
        ]);
    }
}
