<?php

namespace App\Core\Application\Command;

use App\Shared\Application\Command\AsyncCommandInterface;
use App\Shared\Application\Command\CommandAbstract;

final class CreateCurrentPriceCommand extends CommandAbstract implements AsyncCommandInterface
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
}
