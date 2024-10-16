<?php

declare(strict_types=1);

namespace App\Service\RegionalConfiguration;

use App\DataTransferObject\RegionalConfiguration;
use Symfony\Component\HttpFoundation\RequestStack;

final class RegionalConfigurationService
{
    public function configure(RequestStack $requestStack, RegionalConfiguration $regionalConfiguration) : void {
        $session = $requestStack->getSession();

        $session->set('timezone', $regionalConfiguration->getTimezone());
        $session->set('currency', $regionalConfiguration->getCurrency());
        $session->set('_locale', $regionalConfiguration->getLocale());
    }
}