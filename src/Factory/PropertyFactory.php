<?php

declare(strict_types=1);

namespace App\Factory;

use App\DataTransferObject\PropertyCharacteristics;
use App\DataTransferObject\PropertyLocation;
use App\Entity\Property;
use App\Entity\User;

final class PropertyFactory {
    public function createFormDtos(
        User $agent,
        PropertyLocation $propertyLocationDTO,
        PropertyCharacteristics $propertyCharacteristicsDTO
    ) {
        $property = new Property(
            address: $propertyLocationDTO->getAddress(),
            city: $propertyLocationDTO->getCity(),
            state: $propertyLocationDTO->getState(),
            zip_code: $propertyLocationDTO->getZipCode(),
            country: $propertyLocationDTO->getCountry(),
            latitude: $propertyLocationDTO->getLatitude(),
            longtitude: $propertyLocationDTO->getLongtitude(),
            bedrooms: $propertyCharacteristicsDTO->getBedrooms(),
            bathrooms: $propertyCharacteristicsDTO->getBathrooms(),
            sqft: $propertyCharacteristicsDTO->getSqft(),
            acres: $propertyCharacteristicsDTO->getAcres(),
            build_year: $propertyCharacteristicsDTO->getBuildYear(),
            type: $propertyCharacteristicsDTO->getType(),
            status: $propertyCharacteristicsDTO->getStatus(),
            image_path: $propertyCharacteristicsDTO->getImagePath(),
            features: $propertyCharacteristicsDTO->getFeature(),
            description: $propertyCharacteristicsDTO->getDescription(),
            agent: $agent
        );

        return $property;
    }
}