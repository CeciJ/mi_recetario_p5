<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('quantity', [$this, 'formatQuantity']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('unit', [$this, 'formatUnit']),
        ];
    }

    public function formatQuantity($quantity)
    {
        if($quantity >= 1000){
            $quantity = $quantity / 1000;
        }

        return $quantity;
    }

    public function formatUnit($unit, $quantity)
    {
        if($quantity >= 1000){
            switch ($unit) {
                case "g":
                    $unit = 'kg';
                    break;
                case "ml":
                    $unit = 'l';
                    break;
                default:
                    $unit = $unit;
            }
            return $unit;
        } else {
            return $unit;
        }
    }
}