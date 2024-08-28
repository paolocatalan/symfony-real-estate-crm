<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyBase;
use App\Service\Property\PropertyInterface;

class Single extends PropertyBase implements PropertyInterface
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
                'propertyType' => 'Single',
                'bedrooms' => mt_rand(1, 3),
                'banthrooms' => mt_rand(1, 2),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => mt_rand(19001, 209999),
                'listedDate' => '2023-02-18T00:00:00.000Z',
                'lastSeenDate' => '2023-02-23T00:00:00.000Z',
                'daysOld' => mt_rand(1, 30),
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
            'price' => mt_rand(19001, 209999),
            'priceRangeLow' => mt_rand(150000, 190000),
            'priceRangeHigh' => mt_rand(210000, 250000)
        ];
    }

}
