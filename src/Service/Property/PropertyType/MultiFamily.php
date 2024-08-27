<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyInterface;

class MultiFamily implements PropertyInterface
{
    public function compute($property): array
    {

        // Adjust for Differences

        return [
            'price' => 232000,
            'priceRangeHigh' => 251000,
            'priceRangeHigh' => 251000
        ];
    }

    protected function geoCoding(): array
    {
        // get longtitude and latitude
        // https://apidocs.geoapify.com/playground/geocoding/

        return [];
    }

    protected function getPropertyListingsNearBy($address): array
    {
        // get the property listings nearby using geoCoding method
        // get the market value
        
        return [];
    }

    protected function findPropertyMatchTypes($property): array
    {
        // find the most similar sale or rental listings near this property that match its type, size, attributes, age, etc.
        // type, size, and attributes

        return [];
    }

    private function potentialIncome(): array
    {
        // Rental income, vacancy rates, operating expenses, capitalization rate.

        return [];
    }

}
