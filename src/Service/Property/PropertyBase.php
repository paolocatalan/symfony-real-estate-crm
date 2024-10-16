<?php

declare(strict_types=1);

namespace App\Service\Property;

abstract class PropertyBase
{
    # Use DTO here and move the concurrent request to a class
    protected function dataSources($property): array {
        $urls = [
            'https://random-data-api.com/api/v3/projects/09a3ed70-898e-43cd-87dc-93a31ce880ed?' . http_build_query([
                'api_key' => $_ENV['RANDOM_DATA_API']
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
        foreach ($urls as $index => $url) {
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
            'marketData' => $response[0],
            'comparables' => $response[1]['data']
        ];
    }

    abstract protected function comparableListings($property, $geocode): array;

    abstract protected function proprietaryFormula($property, $market, $comparables): array;
}