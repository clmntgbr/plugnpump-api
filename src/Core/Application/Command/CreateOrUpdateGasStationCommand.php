<?php

namespace App\Core\Application\Command;

use App\Dto\GasStationDto;
use App\Shared\Application\Command\AsyncCommandInterface;
use App\Shared\Application\Command\CommandAbstract;

final class CreateOrUpdateGasStationCommand extends CommandAbstract implements AsyncCommandInterface
{
    public function __construct(
        private readonly GasStationDto $gasStation,
    ) {
    }

    public function getGasStation(): GasStationDto
    {
        return $this->gasStation;
    }
}
