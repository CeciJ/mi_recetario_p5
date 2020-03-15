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
            new TwigFunction('formatName', [$this, 'formatName']),
            new TwigFunction('formatDuplicatedNames', [$this, 'formatDuplicatedNames']),
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

    public function formatName($name)
    {
        return $this->enleverCaracteresSpeciaux($name);
    }

    public function formatDuplicatedNames($name)
    {
        if(preg_match('~[0-9]~', $name)){
            $nameFormat = str_split($name);
            foreach($nameFormat as $key => $letter){
                if(is_numeric($letter)){
                    unset($nameFormat[$key]);
                }
            }
            return implode($nameFormat);
        }
        return $name;
    }

    private function enleverCaracteresSpeciaux($text) {
        $string = str_replace(' ', '_', $text);
        return $string;
    }
}