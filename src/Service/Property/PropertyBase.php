<?php

declare(strict_types=1);

namespace App\Service\Property;

abstract class PropertyBase
{
    protected function forwardGeocoding($property): array
    {
        $address = $property->getAddress() . ', ' . $property->getCity() . ', ' . $property->getZipCode() . ', ' . $property->getCountry();

        $geocodeAddress = [
            'text' => $address,
            'limit' => '1',
            'apiKey' => '289606711c714dc2b62b6bc8ca6bc213'
        ];

        $geocodeCities = [
            '_quantity' => '3',
            'date' => 'date',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
            'streetAddress' => 'streetAddress'
        ];

        // URLs to fetch & replace the URLs as per your need
        $urls = [
            'https://api.geoapify.com/v1/geocode/search?' . http_build_query($geocodeAddress),
            'https://fakerapi.it/api/v1/custom?_quantity=3'. http_build_query($geocodeCities)
        ];

        // Initialize multi handle
        $mh = curl_multi_init();
        
        // Array to store cURL handles
        $handles = [];
        
        // Create individual cURL handles for each URL
        foreach ($urls as $url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($mh, $ch);
            $handles[] = $ch;
        }
        
        // Execute the multi handle
        $running = null;
        do {
            curl_multi_exec($mh, $running);
        } while ($running > 0);
        
        // Get and display responses
        foreach ($handles as $index => $ch) {
            $response[$index] = json_decode(curl_multi_getcontent($ch), true);
        }
        
        // Remove handles and close multi handle
        foreach ($handles as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        return [
            'address' => $response[0]['features'][0]['properties'],
            'cities' => $response[1]['data']
        ];
    }

    abstract protected function comparableListings($geocodeAddress, $geocodeCities): array;

    abstract protected function proprietaryFormula($property, $comparables): array;
}