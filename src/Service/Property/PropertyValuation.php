<?php

declare(strict_types=1);

namespace App\Service\Property;

use App\Service\Property\PropertyType\Bungalow;
use App\Service\Property\PropertyType\MultiFamily;
use App\Service\Property\PropertyType\Single;
use App\Service\Property\PropertyType\Townhouse;

class PropertyValuation
{
    private $property;

    private $propertyType;

    public function __construct(object|null $property)
    {
        $this->property = $property;

        switch($property->getType()) {
            case 'Single':
                $this->propertyType = new Single();
                break;
            case 'Townhouse':
                $this->propertyType = new Townhouse();
                break;
            case 'MultiFamily':
                $this->propertyType = new MultiFamily();
                break;
            case 'Bungalow':
                $this->propertyType = new Bungalow();
                break;
            default:
                throw new \Exception('Property Type Not Found', 500);
        }
    }

    public function calculate(): array
    {
        return $this->propertyType->compute($this->property);
    }

}
