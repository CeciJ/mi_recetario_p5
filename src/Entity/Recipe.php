<?php

namespace App\Entity;

use App\Entity\Picture;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 * @UniqueEntity("name")
 */
class Recipe
{
    const COST = [
        1 => 'Économique',
        2 => 'Moyen',
        3 => 'Cher'
    ];

    const DIFFICULTY = [
        1 => 'Facile',
        2 => 'Moyen',
        3 => 'Difficile'
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 50
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $author = "Cécile";

    /**
     * @ORM\Column(type="smallint")
     */
    private $cookingTime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $cost;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="smallint")
     */
    private $difficulty;

    /**
     * @ORM\Column(type="smallint")
     * @Assert\Positive
     */
    private $numberPersons;

    /**
     * @ORM\Column(type="smallint")
     */
    private $preparationTime;

    /**
     * @ORM\Column(type="smallint")
     */
    private $totalTime;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="recipes")
     */
    private $options;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\DishType", inversedBy="recipes")
     */
    private $DishTypes;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\FoodType", inversedBy="recipes")
     */
    private $foodTypes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="recipe", orphanRemoval=true, cascade={"persist"})
     */
    private $pictures;

    /**
     * @Assert\All({ 
     *   @Assert\Image(mimeTypes="image/jpeg")
     * })
     */
    private $pictureFiles;

    /**
     * @var null|Picture
     */
    private $picture;

    /**
     * @ORM\Column(type="text")
     */
    private $steps;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\RecipeIngredients", mappedBy="recipeId")
     */
    private $recipeIngredients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MealPlanning", mappedBy="recipe")
     */
    private $mealPlannings;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->options = new ArrayCollection();
        $this->DishTypes = new ArrayCollection();
        $this->foodTypes = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
        $this->mealPlannings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return (new Slugify())->slugify($this->name);
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(int $cookingTime): self
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function getCostType(): ?string
    {
        return self::COST[$this->cost];
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDifficulty(): ?int
    {
        return $this->difficulty;
    }

    public function getDifficultyType(): ?string
    {
        return self::DIFFICULTY[$this->difficulty];
    }

    public function setDifficulty(int $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getNumberPersons(): ?int
    {
        return $this->numberPersons;
    }

    public function setNumberPersons(int $numberPersons): self
    {
        $this->numberPersons = $numberPersons;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): self
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getTotalTime(): ?int
    {
        return $this->totalTime;
    }

    public function setTotalTime(int $totalTime): self
    {
        $this->totalTime = $totalTime;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addRecipe($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeRecipe($this);
        }

        return $this;
    }

    /**
     * @return Collection|DishType[]
     */
    public function getDishTypes(): Collection
    {
        return $this->DishTypes;
    }

    public function addDishType(DishType $dishType): self
    {
        if (!$this->DishTypes->contains($dishType)) {
            $this->DishTypes[] = $dishType;
        }

        return $this;
    }

    public function removeDishType(DishType $dishType): self
    {
        if ($this->DishTypes->contains($dishType)) {
            $this->DishTypes->removeElement($dishType);
        }

        return $this;
    }

    /**
     * @return Collection|FoodType[]
     */
    public function getFoodTypes(): Collection
    {
        return $this->foodTypes;
    }

    public function addFoodType(FoodType $foodType): self
    {
        if (!$this->foodTypes->contains($foodType)) {
            $this->foodTypes[] = $foodType;
        }

        return $this;
    }

    public function removeFoodType(FoodType $foodType): self
    {
        if ($this->foodTypes->contains($foodType)) {
            $this->foodTypes->removeElement($foodType);
        }

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * setPictureFiles
     * @param  mixed $pictureFiles
     * @return self
     */
    public function setPictureFiles($pictureFiles): self
    {
        foreach($pictureFiles as $pictureFile)
        {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
        }
        $this->pictureFiles = $pictureFiles;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function getPicture(): ?Picture
    {
        if($this->pictures->isEmpty())
        {
            return null;
        } 
        return $this->pictures->first();
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setRecipe($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getRecipe() === $this) {
                $picture->setRecipe(null);
            }
        }

        return $this;
    }

    public function getSteps(): ?string
    {
        return $this->steps;
    }

    public function setSteps(string $steps): self
    {
        $this->steps = $steps;

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
            $recipeIngredient->addRecipeId($this);
        }

        return $this;
    }

    public function removeRecipeIngredient(RecipeIngredients $recipeIngredient): self
    {
        if ($this->recipeIngredients->contains($recipeIngredient)) {
            $this->recipeIngredients->removeElement($recipeIngredient);
            $recipeIngredient->removeRecipeId($this);
        }

        return $this;
    }

    /**
     * @return Collection|MealPlanning[]
     */
    public function getMealPlannings(): Collection
    {
        return $this->mealPlannings;
    }

    public function addMealPlanning(MealPlanning $mealPlanning): self
    {
        if (!$this->mealPlannings->contains($mealPlanning)) {
            $this->mealPlannings[] = $mealPlanning;
            $mealPlanning->setRecipe($this);
        }

        return $this;
    }

    public function removeMealPlanning(MealPlanning $mealPlanning): self
    {
        if ($this->mealPlannings->contains($mealPlanning)) {
            $this->mealPlannings->removeElement($mealPlanning);
            // set the owning side to null (unless already changed)
            if ($mealPlanning->getRecipe() === $this) {
                $mealPlanning->setRecipe(null);
            }
        }

        return $this;
    }

}
