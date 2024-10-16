<?php

declare(strict_types=1);

namespace App\Service\GeoCoding;

use App\DataTransferObject\GeoCodingResponse;
use App\Service\GeoCoding\GeoCodingInterface;

class GooglePlace implements GeoCodingInterface
{
    public function lookup($address, $city, $state, $zipCode, $country): GeoCodingResponse {
        $handle = curl_init();

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?' . http_build_query([
            'address' => $address . ', ' . $city . ', ' . $state . ' ' . $zipCode . ', ' . $country,
            'key' => $_ENV['GOOGLE_PLACES_API']
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

        $body =  json_decode($response, true);

        return new GeoCodingResponse(
            $body['status'],
            $body['results'][0]['geometry']['location']['lat'] ?? null,
            $body['results'][0]['geometry']['location']['lng'] ?? null,
            null,
            null,
            null
        );
    }
}
