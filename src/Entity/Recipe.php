<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Cocur\Slugify\Slugify;
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

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->options = new ArrayCollection();
        $this->DishTypes = new ArrayCollection();
        $this->foodTypes = new ArrayCollection();
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

}
