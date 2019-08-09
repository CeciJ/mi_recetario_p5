<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuyListRepository")
 */
class BuyList
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
    private $startPeriod;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $endPeriod;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartPeriod(): ?int
    {
        return $this->startPeriod;
    }

    public function setStartPeriod(int $startPeriod): self
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
