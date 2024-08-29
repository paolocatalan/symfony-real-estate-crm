<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyBase;
use App\Service\Property\PropertyInterface;

class MultiFamily extends PropertyBase implements PropertyInterface
{
    public function compute($property): array {
        $geocode = $this->forwardGeocoding($property);
        $market = $this->marketData($property);
        $comparables = $this->comparableListings($geocode);
        $formula = $this->proprietaryFormula($property, $market, $comparables); 
        return [
            'price' => $formula['price'],
            'priceRangeLow' => $formula['priceRangeLow'],
            'priceRangeHigh' => $formula['priceRangeHigh'],
            'latitude' => $geocode['address']['lat'],
            'longtitude' => $geocode['address']['lon'],
            'comparables' => $comparables
        ];
    }

    protected function marketData($property): array {
        return [];
    }

    protected function comparableListings($geocode): array {
        $propertyListings = array();
        for ($i=0; $i < 3; $i++) { 
            $propertyListings[] = array_merge($geocode['cities'][$i], array(
                'city' => $geocode['address']['city'],
                'state' => $geocode['address']['state'],
                'zip_code' => $geocode['address']['postcode'],
                'country' => $geocode['address']['country'],
                'propertyType' => 'MultiFamily',
                'bedrooms' => mt_rand(5, 8),
                'banthrooms' => mt_rand(4, 7),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => mt_rand(290000, 310000),
                'listedDate' => '2023-02-18T00:00:00.000Z',
                'lastSeenDate' => '2023-02-23T00:00:00.000Z',
                'daysOld' => 5,
                'distance' => 0.2009,
                'correlation' => 0.9891
            ));
        }
        return $propertyListings;
    }

    protected function proprietaryFormula($market, $property, $comparables): array {
        // Adjustments Factor
        return [
            'price' => mt_rand(290000, 310000),
            'priceRangeLow' => mt_rand(250000, 289999),
            'priceRangeHigh' => mt_rand(310001, 350000)
        ];
    }

}
