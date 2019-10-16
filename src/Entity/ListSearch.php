<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

class ListSearch 
{

    private $startPeriod;

    private $endPeriod;

    public function __construct()
    {
        $fecha = new DateTime();
        $dateStart = $fecha->format('d-m-Y');
        $this->startPeriod = new \DateTime($dateStart);
        $this->endPeriod = new \DateTime();
    }

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