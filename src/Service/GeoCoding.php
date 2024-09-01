<?php

declare(strict_types=1);

namespace App\Service;

class GeoCoding
{
    public function lookup($address, $city, $state, $zipCode, $country): array {
        $addressComponent = $address . ', ' . $city . ', ' . $state . ', ' . $zipCode . ', ' . $country;

        $handle = curl_init();

        $urlParam = [
            'text' => $addressComponent,
            'limit' => '1',
            'apiKey' => $_ENV['GEOAPIFY']
        ];
        $url = 'https://api.geoapify.com/v1/geocode/search?' . http_build_query($urlParam);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handle);

        $retry = 0;
        // CURLE_OPERATION_TIMEDOUT
        while(curl_errno($handle) == 28 && $retry < 3) {
            sleep(1);
            // $this->logger->error('An error occurred');
            $response = curl_exec($handle);
            $retry++;
        }

        return json_decode($response, true);
    }
}