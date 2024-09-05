<?php

declare(strict_types=1);

namespace App\Service\GeoCoding;

use App\DataTransferObject\GeoCodingResponse;
use App\Service\GeoCoding\GeoCodingInterface;

class GeoApify implements GeoCodingInterface
{
    public function lookup($address, $city, $state, $zipCode, $country): GeoCodingResponse {
        $handle = curl_init();

        $url = 'https://api.geoapify.com/v1/geocode/search?' . http_build_query([
            'text' => $address . ', ' . $city . ', ' . $state . ' ' . $zipCode . ', ' . $country,
            'limit' => '1',
            'apiKey' => $_ENV['GEOAPIFY']
        ]);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($handle);

        if(curl_exec($handle) === false) {
            $errno = curl_errno($handle);
            $retry = 0;
            while($errno == 28 && $retry < 3) {
                sleep(1);
                $response = curl_exec($handle);
                $retry++;
            }
            // $logger->error("cURL error: ". curl_strerror($errno));
        }

        $body = json_decode($response, true);

        return new GeoCodingResponse(
            null,
            $body['features'][0]['properties']['lat'],
            $body['features'][0]['properties']['lon'],
            $body['features'][0]['properties']['rank']['confidence'],
            $body['features'][0]['properties']['rank']['confidence_street_level'] ?? null,
            $body['features'][0]['properties']['rank']['confidence_city_level'] ?? null
        );
    }
}
