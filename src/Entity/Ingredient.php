<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
//class Ingredient implements NormalizableInterface
class Ingredient 
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RecipeIngredients", mappedBy="nameIngredient")
     */
    private $recipeIngredients;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\CorrespondingWeightsUnities", inversedBy="ingredients")
     */
    private $weight;

    
    public function __construct()
    {
        $this->recipeIngredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Groups({"searchable"})
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString() 
    {
        return $this->name;
    }

    /**
     * @return Collection|RecipeIngredients[]
     */
    public function getRecipeIngredients(): Collection
    {
        return $this->recipeIngredients;
    }

    public function addRecipeIngredient(RecipeIngredients $recipeIngredient): self
    {
        if (!$this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients[] = $recipeIngredient;
            $recipeIngredient->setNameIngredient($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredients $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
            // set the owning side to null (unless already changed)
            if ($recipeIngredient->getNameIngredient() === $this) {
                $recipeIngredient->setNameIngredient(null);
            }
        }

        return $this;
    }

    public function getWeight(): ?CorrespondingWeightsUnities
    {
        return $this->weight;
    }

    public function setWeight(?CorrespondingWeightsUnities $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    /* public function normalize(NormalizerInterface $serializer, $format = null, array $context = []): array
    {
        return [
            'name' => $this->getName(),
        ];
    } */

}
