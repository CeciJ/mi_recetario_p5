<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CorrespondingWeightsUnitiesRepository")
 */
class CorrespondingWeightsUnities
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Ingredient;

    /**
     * @ORM\Column(type="float")
     */
    private $Weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?string
    {
        return $this->Ingredient;
    }

    public function setIngredient(string $Ingredient): self
    {
        $this->Ingredient = $Ingredient;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->Weight;
    }

    public function setWeight(float $Weight): self
    {
        $this->Weight = $Weight;

        return $this;
    }
}
