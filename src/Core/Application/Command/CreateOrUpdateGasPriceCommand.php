<?php

namespace App\Core\Application\Command;

use App\Dto\GasPriceDto;
use App\Shared\Application\Command\AsyncCommandInterface;
use App\Shared\Application\Command\AsynchronousInterface;
use App\Shared\Application\Command\CommandAbstract;

final class CreateOrUpdateGasPriceCommand implements AsynchronousInterface
{
    public function __construct(
        private readonly string $stationId,
        private readonly GasPriceDto $gasPrice,
    ) {
    }

    public function getGasPrice(): GasPriceDto
    {
        return $this->gasPrice;
    }

    public function getStationId(): string
    {
        return $this->stationId;
    }

    public function getStamps(): array
    {
        return [];
    }
}
