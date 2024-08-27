<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Log\LoggerInterface;

class GeoCoding
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function search(): array
    {
        $handle = curl_init();

        $urlParameters = [
            // 'bias' => 'circle:51.52016005,-0.16030636023551,100',
            // 'keyword' => 'Real Estate',
            // 'radius' => '1500',
            // 'inputtype' => 'textquery',
            // 'address_component' => 'Pl. de Legazpi, 8, Arganzuela, 28045 Madrid, Spain',
            // 'locationbias' => 'circle:51.52016005,-0.16030636023551',
            // 'name' => 'House for Sale',
            // 'key' => $_ENV['GOOGLE_PLACES_API']
            // 'text' => 'Konstanzer Strasse 64, Altenstadt, Freistaat Bayern, 86972, Germany',
            // 'limit' => '1',
            // 'apiKey' => $_ENV['GEOAPIFY']
            '_quantity' => '3',
            // '_locale' => 'de',
            // 'date' => 'date',
            // 'latitude' => 'latitude',
            // 'longitude' => 'longitude',
            'streetAddress' => 'streetAddress',
        ];

        $url = 'https://fakerapi.it/api/v1/custom?'. http_build_query($urlParameters);

        // $url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?'. http_build_query($urlParameters);
        // $url = 'https://maps.googleapis.com/maps/api/place/details/json?'. http_build_query($urlParameters);
        // $url = 'https://api.geoapify.com/v1/geocode/search?'. http_build_query($urlParameters);

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        
        $content = curl_exec($handle);

        $retry = 0;
        // CURLE_OPERATION_TIMEDOUT
        while(curl_errno($handle) == 28 && $retry < 3) {
            sleep(1);
            $this->logger->error('An error occurred');
            $content = curl_exec($handle);
            $retry++;
        }

        $data = json_decode($content, true);
        $propertyListings = $data['data'];
        $propertyListingsCompotents = array();
        for ($i=0; $i < 3; $i++) { 
            $propertyListingsCompotents[] = array_merge($propertyListings[$i], array('country' => 'Germany'));
        }

        dd($propertyListingsCompotents);
        // curl_close($handle);


        return $data;
    }
}