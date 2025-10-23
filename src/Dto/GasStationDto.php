<?php

declare(strict_types=1);

namespace App\Dto;

class GasStationDto
{
    public function __construct(
        private readonly string $id,
        private readonly string $latitude,
        private readonly string $longitude,
        private readonly string $postalCode,
        private readonly string $populationType,
        private readonly string $address,
        private readonly string $city,
        private readonly ?array $services = null,
        private readonly ?array $prices = null,
        private readonly ?array $openingHours = null,
    ) {
    }

    public function getStationId(): string
    {
        return $this->id;
    }

    public function getLatitude(): string
    {
        return $this->latitude;
    }

    public function getLongitude(): string
    {
        return $this->longitude;
    }

    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function getPopulationType(): string
    {
        return $this->populationType;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getServices(): ?array
    {
        return $this->services ?? [];
    }

    public function getPrices(): ?array
    {
        return $this->prices;
    }

    public function getOpeningHours(): ?array
    {
        return $this->openingHours;
    }
}
