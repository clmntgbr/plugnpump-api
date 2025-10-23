<?php

declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Core\Application\Command\CreateOrUpdateGasStationCommand;
use App\Entity\Station;
use App\Repository\StationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateOrUpdateGasStationCommandHandler
{
    public function __construct(
        private StationRepository $stationRepository,
    ) {
    }

    public function __invoke(CreateOrUpdateGasStationCommand $command): void
    {
        $station = $this->stationRepository->findOneBy(['stationId' => $command->getGasStation()->getStationId()]);

        if (null === $station) {
            $station = Station::create($command->getGasStation());
            $this->stationRepository->save($station, true);
        }

        $station->setServices($command->getGasStation()->getServices());

        $this->stationRepository->save($station);
    }
}
