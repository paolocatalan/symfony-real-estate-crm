<?php

declare(strict_types=1);

namespace App\DataTransferObject;

class GeoCodingResponse
{
    public function __construct(
        public readonly string|null $status,
        public readonly float $latitude,
        public readonly float $longtitude,
        public readonly float|null $confidence,
        public readonly float|null $confidenceStreetLevel,
        public readonly float|null $confidenceCityLevel
    ) {}
}