<?php

declare(strict_types=1);

namespace App\Event;

use App\DataTransferObject\RegionalConfiguration;
use App\Service\RegionalConfiguration\RegionalConfigurationService;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;

#[AsEventListener(event:RequestEvent::class, method:'handleTimezone')]
readonly class RegionalConfigurationSubscriber
{
    public function __construct(
        private readonly RegionalConfigurationService $regionalConfigurationService,
        private readonly RequestStack $requestStack
    ) {}

    public function handleTimezone(RequestEvent $event) {
        $session = $event->getRequest()->getSession();

        $timezone = $session->get('timezone');
        $currency = $session->get('currency');
        $locale = $session->get('_locale');

        $session->set('timezone', $timezone);
        $session->set('currency', $currency);
        $session->set('_locale', $locale);

        $regionalConfiguration = new RegionalConfiguration($timezone, $currency, $locale);

        $this->regionalConfigurationService->configure($this->requestStack, $regionalConfiguration);
    }
}
