<?php

namespace App\Service\Property;

use App\Service\Property\PropertyType\Bungalow;
use App\Service\Property\PropertyType\MultiFamily;
use App\Service\Property\PropertyType\Single;
use App\Service\Property\PropertyType\Townhouse;

class PropertyChecker
{
    public static function process($property)
    {
        $propertyType = $property->getType();

        switch($propertyType) {
            case 'Single':
                $propertyTypeClass = new Single($property);
                break;
            case 'Townhouse':
                $propertyTypeClass = new Townhouse($property);
                break;
            case 'MultiFamily':
                $propertyTypeClass = new MultiFamily($property);
                break;
            case 'Bungalow':
                $propertyTypeClass = new Bungalow($property);
                break;
            default:
                throw new \Exception("Property Type Not Found", 500);
        }

        $marketValue = new MarketValue($propertyTypeClass);
        $propertyEvaluation = $marketValue->compute();

        return $propertyEvaluation;
    }

}
