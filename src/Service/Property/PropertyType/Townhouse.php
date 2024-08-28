<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyBase;
use App\Service\Property\PropertyInterface;

class Townhouse extends PropertyBase implements PropertyInterface
{
    public function compute($property): array
    {
        $geocode = $this->forwardGeocoding($property);

        $comparables = $this->comparableListings($geocode['address'], $geocode['cities']);

        $formula = $this->proprietaryFormula($property, $comparables); 

        return [
            'price' => $formula['price'],
            'priceRangeLow' => $formula['priceRangeLow'],
            'priceRangeHigh' => $formula['priceRangeHigh'],
            'latitude' => $geocode['address']['lat'],
            'longtitude' => $geocode['address']['lon'],
            'comparables' => $comparables
        ];
    }

    protected function comparableListings($geocodeAddress, $geocodeCities): array
    {
        $propertyListings = array();
        for ($i=0; $i < 3; $i++) { 
            $propertyListings[] = array_merge($geocodeCities[$i], array(
                'city' => $geocodeAddress['city'],
                'state' => $geocodeAddress['state'],
                'zip_code' => $geocodeAddress['postcode'],
                'country' => $geocodeAddress['country'],
                'propertyType' => 'Townhouse',
                'bedrooms' => mt_rand(3, 4),
                'banthrooms' => mt_rand(2, 3),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => mt_rand(240000, 260000),
                'listedDate' => '2023-02-18T00:00:00.000Z',
                'lastSeenDate' => '2023-02-23T00:00:00.000Z',
                'daysOld' => 5,
                'distance' => 0.2009,
                'correlation' => 0.9891
            ));
        }

        return $propertyListings;
    }

    protected function proprietaryFormula($property, $comparables): array
    {
        // Market Trends

        // Adjustments Factor

        return [
            'price' => mt_rand(240000, 260000),
            'priceRangeLow' => mt_rand(200000, 239999),
            'priceRangeHigh' => mt_rand(260001, 280000)
        ];
    }

}
