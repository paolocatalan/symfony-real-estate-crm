<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyBase;
use App\Service\Property\PropertyInterface;

class Bungalow extends PropertyBase implements PropertyInterface
{
    public function compute($property): array {
        $data = $this->dataSources($property);
        $comparables = $this->comparableListings($property, $data['comparables']);
        $formula = $this->proprietaryFormula($property, $data['marketData'], $comparables); 
        return [
            'price' => $formula['price'],
            'priceRangeLow' => $formula['priceRangeLow'],
            'priceRangeHigh' => $formula['priceRangeHigh'],
            'latitude' => $property->getLatitude(),
            'longtitude' => $property->getLongtitude(),
            'comparables' => $comparables
        ];
    }

    protected function comparableListings($property, $geocodeCities): array {
        $propertyListings = array();
        for ($i=0; $i < 3; $i++) { 
            $propertyListings[] = array_merge($geocodeCities[$i], array(
                'city' => $property->getCity(),
                'state' => $property->getState(),
                'zip_code' => $property->getZipCode(),
                'country' => $property->getCountry(),
                'propertyType' => 'Bungalow',
                'bedrooms' => mt_rand(1, 3),
                'banthrooms' => mt_rand(1, 2),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => mt_rand(140001, 159999),
                'listedDate' => '2023-02-18T00:00:00.000Z',
                'lastSeenDate' => '2023-02-23T00:00:00.000Z',
                'daysOld' => 5,
                'distance' => 0.2009,
                'correlation' => 0.9891
            ));
        }
        return $propertyListings;
    }

    protected function proprietaryFormula($property, $market, $comparables): array {
        // Adjustments Factor
        return [
            'price' => mt_rand(140001, 159999),
            'priceRangeLow' => mt_rand(120000, 140000),
            'priceRangeHigh' => mt_rand(160000, 180000)
        ];
    }

}
