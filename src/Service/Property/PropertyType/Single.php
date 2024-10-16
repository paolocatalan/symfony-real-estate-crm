<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyBase;
use App\Service\Property\PropertyInterface;

class Single extends PropertyBase implements PropertyInterface
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
                'propertyType' => 'Single',
                'bedrooms' => mt_rand(1, 3),
                'banthrooms' => mt_rand(1, 2),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => mt_rand(19001, 209999),
                'listedDate' => date('Y-m-d', strtotime('- '.  mt_rand(1, 30) . ' days' )),
                'lastSeenDate' => date('Y-m-d', strtotime('- '.  mt_rand(1, 30) . ' days' )),
                'daysOld' => mt_rand(1, 30),
                'distance' => (float)number_format(mt_rand() / mt_getrandmax(), 4),
                'correlation' => (float)number_format(mt_rand() / mt_getrandmax(), 4)
            ));
        }
        return $propertyListings;
    }

    protected function proprietaryFormula($market, $property, $comparables): array {
        // Adjustments Factor
        return [
            'price' => mt_rand(19001, 209999),
            'priceRangeLow' => mt_rand(150000, 190000),
            'priceRangeHigh' => mt_rand(210000, 250000)
        ];
    }

}
