<?php

declare(strict_types=1);

namespace App\Config;

enum UserRoleEnum: string
{
    case Broker = 'Broker';
    case PropertyOwner = 'Property Owner';
    case FinancialProviders = 'Financial Providers';
}