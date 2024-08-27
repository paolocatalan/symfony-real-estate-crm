<?php

declare(strict_types=1);

namespace App\Service\Property\PropertyType;

use App\Service\Property\PropertyInterface;

class Single implements PropertyInterface
{
    public function compute($property): array
    {
        $formattedAddress = $property->getAddress() . ', ' . $property->getCity() . ', ' . $property->getZipCode() . ', ' . $property->getCountry();

        $geocodeAddress = $this->forwardGeocoding($formattedAddress);

        $comparables = $this->comparableListings($geocodeAddress);

        $formula = $this->proprietaryFormula($property, $comparables); 

        return [
            'price' => $formula['price'],
            'priceRangeLow' => $formula['priceRangeLow'],
            'priceRangeHigh' => $formula['priceRangeHigh'],
            'latitude' => $geocodeAddress['lat'],
            'longtitude' => $geocodeAddress['lon'],
            'comparables' => $comparables
        ];
    }

    protected function forwardGeocoding($address): array
    {
        $handle = curl_init();

        $params = [
            'text' => $address,
            'limit' => '1',
            'apiKey' => $_ENV['GEOAPIFY']
        ];
        $url = 'https://api.geoapify.com/v1/geocode/search?'. http_build_query($params);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($handle);
        
        $retry = 0;
        while(curl_errno($handle) == 28 && $retry < 3) {
            sleep(1);
            $response = curl_exec($handle);
            $retry++;
        }

        $geocodeAddress = json_decode($response, true);

        curl_close($handle);

        return $geocodeAddress['features'][0]['properties'];
    }

    protected function comparableListings($geocodeAddress): array
    {
        $handle = curl_init();

        $urlParams = [
            '_quantity' => '3',
            'date' => 'date',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'streetAddress' => 'streetAddress'
        ];

        $url = 'https://fakerapi.it/api/v1/custom?_quantity=3'. http_build_query($urlParams);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($handle);
        
        $retry = 0;
        while(curl_errno($handle) == 28 && $retry < 3) {
            sleep(1);
            $response = curl_exec($handle);
            $retry++;
        }

        $content = json_decode($response, true);
        $propertyListings = $content['data'];
        
        $properties = array();
        for ($i=0; $i < 3; $i++) { 
            $properties[] = array_merge($propertyListings[$i], array(
                'city' => $geocodeAddress['city'],
                'state' => $geocodeAddress['state'],
                'zip_code' => $geocodeAddress['postcode'],
                'country' => $geocodeAddress['country'],
                'propertyType' => 'Single',
                'bedrooms' => mt_rand(1, 3),
                'banthrooms' => mt_rand(1, 2),
                'squareFootage' => mt_rand(1000, 1800),
                'price' => rand(19001, 209999),
                'listedDate' => '2023-02-18T00:00:00.000Z',
                'lastSeenDate' => '2023-02-23T00:00:00.000Z',
                'daysOld' => 5,
                'distance' => 0.2009,
                'correlation' => 0.9891
            ));
        }

        return $properties;
    }

    protected function proprietaryFormula($property, $comparables): array
    {
        // Market Trends

        // Adjustments Factor

        return [
            'price' => rand(19001, 209999),
            'priceRangeLow' => rand(150000, 190000),
            'priceRangeHigh' => rand(210000, 250000)
        ];
    }

}
