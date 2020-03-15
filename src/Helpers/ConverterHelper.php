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
     * converter
     * si g ou kg => on affiche en g => si correspondance => on affiche en unités
     * si ml, cl ou l => on affiche en cl
     * @param  mixed $ingredient
     * @return void
     */
    public function unifyUnity(RecipeIngredients $ingredient)
    {
        // Get ingredients characteristics
        $name = $ingredient->getNameIngredient()->getName();
        $unit = $ingredient->getUnit();
        $quantity = $ingredient->getQuantity();

        // Set reference units : g, cl, unité(s), c. à café
        $newUnitG = $this->unitRepo->findOneBy(['unit' => 'g']);
        $newUnitCl = $this->unitRepo->findOneBy(['unit' => 'cl']);
        $newUnitUnité = $this->unitRepo->findOneBy(['unit' => 'unité(s)']);
        $newUnitSpoon = $this->unitRepo->findOneBy(['unit' => 'c. à café']);

        // Check if ingredient has corresponding weight
        $ingredientToCheck = $this->correspondanceRepo->findOneBy(['Ingredient' => $name]);

        // Switch on the unit to convert the quantity
        switch ($unit) {
            case 'g':
                if($ingredientToCheck){
                    $ingredient->setQuantity($quantity / $ingredientToCheck->getWeight());
                    $ingredient->setUnit($newUnitUnité);
                }
                break;
            case 'kg':
                // From kg to g
                $quantity = $quantity * 1000;
                if($ingredientToCheck){
                    $ingredient->setQuantity($quantity / $ingredientToCheck->getWeight());
                    $ingredient->setUnit($newUnitUnité);
                    break;
                }
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitG);
                break;
            case 'ml':
                // From ml to cl
                $quantity = $quantity / 10;
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitCl);
                break;
            case 'cl':
                break;
            case 'l':
                // From l to cl
                $quantity = $quantity * 100;
                $ingredient->setQuantity($quantity);
                $ingredient->setUnit($newUnitCl);
                break;
            case 'c. à café':
                break;
            case 'c. à soupe':
                // From c. à soupe to c. à café (1 c.à soupe = 3 c.à café)
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