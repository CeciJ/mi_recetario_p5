<?php

namespace App\Helpers;

use App\Entity\RecipeIngredients;
use App\Repository\CorrespondingWeightsUnitiesRepository;
use App\Repository\MeasureUnitRepository;

class ConverterHelper {

    private $unitRepo;

    private $correspondanceRepo;

    public function __construct(MeasureUnitRepository $repoUnit, CorrespondingWeightsUnitiesRepository $repoCorrespondance)
    {
        $this->unitRepo = $repoUnit;
        $this->correspondanceRepo = $repoCorrespondance;
    }
    
    /**
     * convert
     * si g ou kg => on affiche en g
     * si ml, cl ou l => on affiche en cl
     * si l'ingrédient a un poids défini en correspondance => on affiche en unités
     * @param  mixed $ingredient
     * @return void
     */
    public function unifyUnity(RecipeIngredients $ingredient){
        // On récupère les caractéristiques de l'ingrédient
        $name = $ingredient->getNameIngredient()->getName();
        $unit = $ingredient->getUnit();
        $quantity = $ingredient->getQuantity();

        // On récupère les entités measureUnit de référence : g, cl, unité(s), c. à café
        $newUnitG = $this->unitRepo->findOneBy(['unit' => 'g']);
        $newUnitCl = $this->unitRepo->findOneBy(['unit' => 'cl']);
        $newUnitUnité = $this->unitRepo->findOneBy(['unit' => 'unité(s)']);
        $newUnitSpoon = $this->unitRepo->findOneBy(['unit' => 'c. à café']);

        // On check si l'ingrédient a une correspondance poids/unités
        $ingredientToCheck = $this->correspondanceRepo->findOneBy(['Ingredient' => $name]);

        // On switch sur l'unité pour convertir la quantité
        switch ($unit) {
            case 'g':
                if($ingredientToCheck){
                    $ingredient->setQuantity($ingredientToCheck->getWeight());
                    $ingredient->setUnit($newUnitUnité);
                }
                break;
            case 'kg':
                // On transforme les kg en g
                $quantity = $quantity * 1000;
                if($ingredientToCheck){
                    $ingredient->setQuantity($quantity/$ingredientToCheck->getWeight());
                    $ingredient->setUnit($newUnitUnité);
                    break;
                }
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitG);
                break;
            case 'ml':
                // On transforme les ml en cl
                $quantity = $quantity / 10;
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitCl);
                break;
            case 'cl':
                break;
            case 'l':
                // On transforme les l en cl
                $quantity = $quantity * 100;
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitCl);
                break;
            case 'c. à café':
                break;
            case 'c. à soupe':
                // On transforme les c. à soupe en c. à café (1 c.à soupe = 3 c.à café)
                $quantity = $quantity * 3;
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitSpoon);
                break;
            default:
                break;
        }
        return $ingredient;        
    }
}