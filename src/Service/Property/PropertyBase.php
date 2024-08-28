<?php

declare(strict_types=1);

namespace App\Service\Property;

abstract class PropertyBase
{
    protected function forwardGeocoding($property): array
    {
        $address = $property->getAddress() . ', ' . $property->getCity() . ', ' . $property->getZipCode() . ', ' . $property->getCountry();

        $handle = curl_init();

        $params = [
            'text' => $address,
            'limit' => '1',
            'apiKey' => $_ENV['GEOAPIFY']
        ];
        $url = 'https://api.geoapify.com/v1/geocode/search?' . http_build_query($params);

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

    abstract protected function comparableListings($geocodeAddress): array;

    abstract protected function proprietaryFormula($property, $comparables): array;
}