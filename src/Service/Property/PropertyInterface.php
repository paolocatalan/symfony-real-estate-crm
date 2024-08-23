<?php

declare(strict_types = 1);

namespace App\Service\Property;

interface PropertyInterface
{
    public function calculate($priceStartsAt): float;
}