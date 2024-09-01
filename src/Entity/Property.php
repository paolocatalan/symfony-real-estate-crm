<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $state = null;

    #[ORM\Column(length: 255)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $longtitude = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $features = null;

    #[ORM\Column(length: 255)]
    private ?string $image_path = null;

    #[ORM\Column]
    private ?int $bedrooms = null;

    #[ORM\Column]
    private ?int $bathrooms = null;

    #[ORM\Column]
    private ?float $sqft = null;

    #[ORM\Column]
    private ?float $acres = null;

    #[ORM\Column]
    private ?int $build_year = null;

    #[ORM\Column(type: 'string')]
    private ?string $type;

    #[ORM\Column(length: 255)]
    private ?string $status;

    #[ORM\ManyToOne]
    private ?User $broker = null;

    #[ORM\ManyToOne(inversedBy: 'property')]
    private ?User $agent = null;

    public function __construct(
        null|string $address = null,
        null|string $city = null,
        null|string $state = null,
        null|string $zip_code = null,
        null|string $country = null,
        null|float $latitude = null,
        null|float $longtitude = null,
        null|string $description = null,
        null|string $features = null,
        null|string $image_path = null,
        null|int $bedrooms = null,
        null|int $bathrooms = null,
        null|float $sqft = null,
        null|float $acres = null,
        null|int $build_year = null,
        null|string $type = null,
        null|string $status = null,
        null|string $broker = null,
        null|User $agent = null,
    )
    {
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->zip_code = $zip_code;
        $this->country = $country;
        $this->latitude = $latitude;
        $this->longtitude = $longtitude;
        $this->description = $description;
        $this->features = $features;
        $this->image_path = $image_path;
        $this->bedrooms = $bedrooms;
        $this->bathrooms = $bathrooms;
        $this->sqft = $sqft;
        $this->acres = $acres;
        $this->build_year = $build_year;
        $this->type = $type;
        $this->status = $status;
        $this->broker = $broker;
        $this->agent = $agent;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getFeatures(): ?string
    {
        return $this->features;
    }

    public function setFeatures(string $features): static
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

    public function setAcres(string $acres): static
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

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

}
