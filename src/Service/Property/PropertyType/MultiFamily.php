<?php

declare(strict_types = 1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyInterface;

class MultiFamily implements PropertyInterface
{
    public $property;

    public function __construct(object|null $property)
    {
        $this->property = $property;
    }

    public function calculate($priceStartsAt): float
    {
        $value = $priceStartsAt + $this->geography($this->property) + $this->valuation($this->property) + $this->financing($this->property); 

        return $value;
    }

    private function geography($property): float
    {
        // $property['address']
        return rand(100, 900);
    }

    private function valuation($property): float
    {
        // $property['characteristics']
        return rand(100, 900);
    }

    private function financing($property): float
    {
        // $property
        return rand(100, 900);
    }

}
