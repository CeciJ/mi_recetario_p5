<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MealPlanningRepository")
 */
class MealPlanning
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $beginAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", mappedBy="mealPlanning")
     */
    private $recipesData;

    public function __construct()
    {
        $this->recipes = new ArrayCollection();
        $this->recipesData = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBeginAt(): ?\DateTimeInterface
    {
        return $this->beginAt;
    }

    public function setBeginAt(\DateTimeInterface $beginAt): self
    {
        $this->beginAt = $beginAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(?\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    /**
     * @return Collection|Recipe[]
     */
    public function getRecipesData(): Collection
    {
        return $this->recipesData;
    }

    public function addRecipesData(Recipe $recipesData): self
    {
        if (!$this->recipesData->contains($recipesData)) {
            $this->recipesData[] = $recipesData;
            $recipesData->addMealPlanning($this);
        }

        return $this;
    }

    public function removeRecipesData(Recipe $recipesData): self
    {
        if ($this->recipesData->contains($recipesData)) {
            $this->recipesData->removeElement($recipesData);
            $recipesData->removeMealPlanning($this);
        }

        return $this;
    }
}
