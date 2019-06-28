<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Recipe;
use App\Entity\DishType;
use App\Entity\FoodType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('pictureFiles', FileType::class, [
                'required' => false,
                'multiple' => true
            ])
            ->add('cookingTime')
            ->add('cost', ChoiceType::class, [
                "choices" => $this->getCostChoices()
            ])
            ->add('difficulty', ChoiceType::class, [
                "choices" => $this->getDifficultyChoices()
            ])
            ->add('numberPersons')
            ->add('preparationTime')
            ->add('totalTime')
            ->add('DishTypes', EntityType::class, [
                'class' => DishType::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('foodTypes', EntityType::class, [
                'class' => FoodType::class,
                'choice_label' => 'name',
                'multiple' => true
            ])
            ->add('options', EntityType::class, [
                'class' => Option::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getCostChoices()
    {
        $choices = Recipe::COST;
        return $this->getChoices($choices);
    }

    private function getDifficultyChoices()
    {
        $choices = Recipe::DIFFICULTY;
        return $this->getChoices($choices);
    }

    private function getChoices($var)
    {
        $output = [];
        foreach($var as $k => $v)
        {
            $output[$v] = $k;
        }
        return $output;
    }
}
