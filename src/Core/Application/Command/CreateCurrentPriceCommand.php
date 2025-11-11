<?php

namespace App\Core\Application\Command;

use App\Shared\Application\Command\AsyncCommandInterface;
use App\Shared\Application\Command\AsynchronousInterface;
use App\Shared\Application\Command\CommandAbstract;

final class CreateCurrentPriceCommand implements AsynchronousInterface
{
    public function __construct(
        private readonly string $stationId,
        private readonly string $name,
        private readonly string $id,
        private readonly \DateTime $updatedAt,
        private readonly float $value,
    ) {
    }

    public function getStationId(): string
    {
        return $this->stationId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getStamps(): array
    {
        return [];
    }
}
