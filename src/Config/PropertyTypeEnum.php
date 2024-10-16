<?php

declare(strict_types=1);

namespace App\Config;

enum PropertyTypeEnum: string
{
    case Single = 'Single';
    case Townhouse = 'Townhouse';
    case MultiFamily = 'MultiFamily';
    case Bungalow = 'Bungalow';
}
