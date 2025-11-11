<?php

namespace App\Core\Application\Command;

use App\Dto\GasStationDto;
use App\Shared\Application\Command\AsyncCommandInterface;
use App\Shared\Application\Command\AsynchronousInterface;
use App\Shared\Application\Command\CommandAbstract;

final class CreateOrUpdateGasStationCommand implements AsynchronousInterface
{
    public function __construct(
        private readonly GasStationDto $gasStation,
    ) {
    }

    public function getGasStation(): GasStationDto
    {
        return $this->gasStation;
    }

    public function getStamps(): array
    {
        return [];
    }
}
