<?php

declare(strict_types = 1);

namespace App\Service\Property;

use App\Service\Property\PropertyInterface;
use App\Service\Property\PropertyType\Bungalow;
use App\Service\Property\PropertyType\MultiFamily;
use App\Service\Property\PropertyType\Single;
use App\Service\Property\PropertyType\Townhouse;

class MarketValue
{

    private $propertyInterface;

    public function __construct(PropertyInterface $propertyInterface)
    {
        $this->propertyInterface = $propertyInterface;
    }

    public function compute(): float
    {
        $propertyType = $this->propertyInterface;
        switch(true) {
            case $propertyType instanceof Single:
                $priceStartsAt = 1000;
                break;
            case $propertyType instanceof Townhouse:
                $priceStartsAt = 5000;
                break;
            case $propertyType instanceof MultiFamily:
                $priceStartsAt = 10000;
                break;
            case $propertyType instanceof Bungalow:
                $priceStartsAt = 500;
                break;
            default:
                throw new \Exception("Property Type Not Found", 500);
                
        }

        return $this->propertyInterface->calculate($priceStartsAt);
    }
}
