<?php

declare(strict_types=1);

namespace App\Service\Property;

abstract class PropertyBase
{
    protected function dataSources($property): array {
        $address = $property->getAddress() . ', ' . $property->getCity() . ', ' . $property->getZipCode() . ', ' . $property->getCountry();

        $urls = [
            'https://api.geoapify.com/v1/geocode/search?' . http_build_query([
                'text' => $address,
                'limit' => '1',
                'apiKey' => $_ENV['GEOAPIFY']
            ]),
            'https://fakerapi.it/api/v1/custom?'. http_build_query([
                '_quantity' => '3',
                'date' => 'date',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'streetAddress' => 'streetAddress'
            ])
        ];

        $multiHandle = curl_multi_init();
        $handles = [];
        foreach ($urls as $url) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_multi_add_handle($multiHandle, $curl);
            $handles[] = $curl;
        }
        
        try {
            $running = null;
            do {
                $status = curl_multi_exec($multiHandle, $running);
                // block the loop until there's activity on any curl_multi connection
                curl_multi_select($multiHandle, 1);
            } while ($running > 0);

            if ($status !== CURLM_OK) {
                $errno = curl_multi_errno($multiHandle);
                $errorMessage = curl_multi_strerror($status);
                throw new \Exception($errorMessage, $errno);
            }
        } catch (\Exception $e) {
            //$logger->error($e->getMessage());
            return [];
        }

        foreach ($handles as $index => $curl) {
            $response[$index] = json_decode(curl_multi_getcontent($curl), true);
        }
        
        foreach ($handles as $curl) {
            curl_multi_remove_handle($multiHandle, $curl);
            curl_close($curl);
        }
        curl_multi_close($multiHandle);

        return [
            'forwardGeocoding' => $response[0]['features'][0]['properties'],
            'comparables' => $response[1]['data'],
            'marketData' => []
        ];
    }

    abstract protected function comparableListings($property, $geocode): array;

    abstract protected function proprietaryFormula($property, $market, $comparables): array;
}