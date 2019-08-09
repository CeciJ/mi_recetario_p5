<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

class ListSearch 
{

    private $startPeriod;

    private $endPeriod;


    public function getStartPeriod(): ?\DateTime
    {
        return $this->startPeriod;
    }

    public function setStartPeriod(\DateTimeInterface $startPeriod): self
    {
        $this->startPeriod = $startPeriod;

        return $this;
    }

    public function getEndPeriod(): ?\DateTimeInterface
    {
        return $this->endPeriod;
    }

    public function setEndPeriod(?\DateTimeInterface $endPeriod): self
    {
        $this->endPeriod = $endPeriod;

        return $this;
    }

}