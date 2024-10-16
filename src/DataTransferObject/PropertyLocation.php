<?php

declare(strict_types=1);

namespace App\DataTransferObject;

class PropertyLocation
{
    private null|string $address = null;
    private null|string $city = null;
    private null|string $state = null;
    private null|string $zipCode = null;
    private null|string $country = null;
    private null|float $latitude = null;
    private null|float $longtitude = null;

    public function getAddress(): ?string {
        return $this->address;
    }

    public function setAddress(?string $address): void {
        $this->address = $address;
    }

    public function getCity(): ?string {
        return $this->city;
    }

    public function setCity(?string $city): void {
        $this->city = $city;
    }

    public function getState(): ?string {
        return $this->state;
    }

    public function setState(?string $state): void {
        $this->state = $state;
    }

    public function getZipCode(): ?string {
        return $this->zipCode;
    }

    public function setZipCode(?string $zipCode): void {
        $this->zipCode = $zipCode;
    }

    public function getCountry(): ?string {
        return $this->country;
    }

    public function setCountry(?string $country): void {
        $this->country = $country;
    }

    public function getLatitude(): ?float {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): void {
        $this->latitude = $latitude;
    }

    public function getLongtitude(): ?float {
        return $this->longtitude;
    }

    public function setLongtitude(?float $longtitude): void {
        $this->longtitude = $longtitude;
    }


}