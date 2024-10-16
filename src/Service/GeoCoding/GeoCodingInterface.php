<?php

declare(strict_types=1);

namespace App\Service\GeoCoding;

use App\DataTransferObject\GeoCodingResponse;

interface GeoCodingInterface
{
    public function lookup($address, $city, $state, $zipCode, $country): GeoCodingResponse;
}