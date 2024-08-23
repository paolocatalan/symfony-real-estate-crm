<?php

declare(strict_types = 1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyInterface;

class Townhouse implements PropertyInterface
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
        // Get API's data
        $zipCode = $property->getZipCode();
        $jackUpThePrice = array("786", "9866", "8797", "9865");
        if (in_array($zipCode, $jackUpThePrice) ) {
            return rand(900, 2000);
        }
        return rand(100, 900);
    }

    private function valuation($property): float
    {
        // Get API's data
        // $property['characteristics']
        return rand(100, 900);
    }

    private function financing($property): float
    {
        // Get API's data
        // $property
        return rand(100, 900);
    }

}
