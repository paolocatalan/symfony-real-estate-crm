<?php

declare(strict_types=1);

namespace App\DataTransferObject;

use App\Entity\User;

class PropertyCharacteristics
{
    private null|string $description = null;
    private null|string $features = null;
    private null|string $image_path = null;
    private null|int $bedrooms = null;
    private null|int $bathrooms = null;
    private null|float $sqft = null;
    private null|float $acres = null;
    private null|int $build_year = null;
    private null|string $type = null;
    private null|string $status = null;
    private null|string $broker = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFeature(): ?string
    {
        return $this->features;
    }

    public function setFeature(string $features): static
    {
        $this->features = $features;

        return $this;
    }

    public function getImagePath(): ?string
    {
        return $this->image_path;
    }

    public function setImagePath(string $image_path): static
    {
        $this->image_path = $image_path;

        return $this;
    }

    public function getBedrooms(): ?int
    {
        return $this->bedrooms;
    }

    public function setBedrooms(int $bedrooms): static
    {
        $this->bedrooms = $bedrooms;

        return $this;
    }

    public function getBathrooms(): ?int
    {
        return $this->bathrooms;
    }

    public function setBathrooms(int $bathrooms): static
    {
        $this->bathrooms = $bathrooms;

        return $this;
    }

    public function getSqft(): ?float
    {
        return $this->sqft;
    }

    public function setSqft(float $sqft): static
    {
        $this->sqft = $sqft;

        return $this;
    }

    public function getAcres(): ?float
    {
        return $this->acres;
    }

    public function setAcres(float $acres): static
    {
        $this->acres = $acres;

        return $this;
    }

    public function getBuildYear(): ?int
    {
        return $this->build_year;
    }

    public function setBuildYear(int $build_year): static
    {
        $this->build_year = $build_year;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getBroker(): ?User
    {
        return $this->broker;
    }

    public function setBroker(?User $broker): static
    {
        $this->broker = $broker;

        return $this;
    }
}