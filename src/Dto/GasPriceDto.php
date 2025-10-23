<?php

declare(strict_types=1);

namespace App\Dto;

class GasPriceDto
{
    public function __construct(
        private readonly string $name,
        private readonly string $id,
        private readonly string $updatedAt,
        private readonly float $value,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
