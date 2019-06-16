<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class RecipeSearch 
{
    /**
     * @var int|null
     */
    private $maxTotalTime;

    public function getMaxTotalTime(): ?int
    {
		return $this->maxTotalTime;
	}

    public function setMaxTotalTime(int $maxTotalTime): RecipeSearch 
    {
        $this->maxTotalTime = $maxTotalTime;
        return $this;
	}

    /**
     * @var int|null
     * @Assert\Range(min=1, max=8)
     */
    private $numberPersons;

    public function getNumberPersons(): ?int
    {
		return $this->numberPersons;
    }

    public function setNumberPersons(int $numberPersons): RecipeSearch
    {
        $this->numberPersons = $numberPersons;
        return $this;
	}
}