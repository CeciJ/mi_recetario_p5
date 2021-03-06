<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeIngredientsRepository")
 */
class RecipeIngredients
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MeasureUnit", inversedBy="recipeIngredients", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ingredient", inversedBy="recipeIngredients", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private $nameIngredient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Recipe", inversedBy="recipeIngredients", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipe;


    public function __construct()
    {
        $this->ingredient = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnit(): ?MeasureUnit
    {
        return $this->unit;
    }

    public function setUnit(?MeasureUnit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): self
    {
        $this->recipe = $recipe;

        return $this;
    }

    public function getNameIngredient(): ?Ingredient
    {
        return $this->nameIngredient;
    }

    public function setNameIngredient(?Ingredient $nameIngredient): self
    {
        $this->nameIngredient = $nameIngredient;

        return $this;
    }

}
