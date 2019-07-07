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
     * @ORM\ManyToMany(targetEntity="App\Entity\MeasureUnit", inversedBy="recipeIngredients", fetch="EAGER")
     */
    private $unit;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ingredient", inversedBy="recipeIngredients", fetch="EAGER")
     */
    private $ingredient;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", inversedBy="recipeIngredients", fetch="EAGER")
     */
    private $recipeId;


    public function __construct()
    {
        $this->unit = new ArrayCollection();
        $this->ingredient = new ArrayCollection();
        $this->recipeId = new ArrayCollection();
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

    /**
     * @return Collection|MeasureUnit[]
     */
    public function getUnit(): Collection
    {
        return $this->unit;
    }

    public function addUnit(MeasureUnit $unit): self
    {
        if (!$this->unit->contains($unit)) {
            $this->unit[] = $unit;
        }

        return $this;
    }

    public function removeUnit(MeasureUnit $unit): self
    {
        if ($this->unit->contains($unit)) {
            $this->unit->removeElement($unit);
        }

        return $this;
    }

    /**
     * @return Collection|Ingredient[]
     */
    public function getIngredient(): Collection
    {
        return $this->ingredient;
    }

    public function addIngredient(Ingredient $ingredient): self
    {
        if (!$this->ingredient->contains($ingredient)) {
            $this->ingredient[] = $ingredient;
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): self
    {
        if ($this->ingredient->contains($ingredient)) {
            $this->ingredient->removeElement($ingredient);
        }

        return $this;
    }

    public function __toString() 
    {
        return $this->name;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipeId(): Collection
    {
        return $this->recipeId;
    }

    public function addRecipeId(Recipe $recipeId): self
    {
        if (!$this->recipeId->contains($recipeId)) {
            $this->recipeId[] = $recipeId;
        }

        return $this;
    }

    public function removeRecipeId(Recipe $recipeId): self
    {
        if ($this->recipeId->contains($recipeId)) {
            $this->recipeId->removeElement($recipeId);
        }

        return $this;
    }

}
