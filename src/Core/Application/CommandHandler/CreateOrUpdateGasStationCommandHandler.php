<?php

declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Core\Application\Command\CreateOrUpdateGasPriceCommand;
use App\Core\Application\Command\CreateOrUpdateGasStationCommand;
use App\Dto\GasPriceDto;
use App\Entity\Station;
use App\Repository\StationRepository;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateOrUpdateGasStationCommandHandler
{
    public function __construct(
        private StationRepository $stationRepository,
        private CommandBusInterface $commandBus,
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

        array_map(function (GasPriceDto $price) use ($station) {
            $this->commandBus->dispatch(new CreateOrUpdateGasPriceCommand($station->getStationId(), $price));
        }, $command->getGasStation()->getPrices() ?? []);

        $this->stationRepository->save($station);
    }
}
