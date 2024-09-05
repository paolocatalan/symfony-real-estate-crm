<?php

declare(strict_types=1);

namespace App\DataTransferObject;

final class RegionalConfiguration
{
    private string|null $timezone = null;
    private string|null $currency = null;
    private string|null $locale = null;

    public function __construct(string|null $timezone = null, string|null $currency = null, string|null $locale = null)
    {
        $this->timezone = $timezone;
        $this->currency = $currency;
        $this->locale = $locale;
    }

    public function getTimezone(): ?string {
        return $this->timezone;
    }

    public function setTimezone(?string $timezone): void {
        $this->timezone = $timezone;
    }

    public function getCurrency(): ?string {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void {
        $this->currency = $currency;
    }

    public function getLocale(): ?string {
        return $this->locale;
    }

    public function setLocale(?string $locale): void {
        $this->locale = $locale;
    }
}