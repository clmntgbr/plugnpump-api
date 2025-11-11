<?php

declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Core\Application\Command\CreateCurrentPriceCommand;
use App\Core\Application\Command\CreateOrUpdateGasPriceCommand;
use App\Core\Application\Command\CreatePriceHistoryCommand;
use App\Entity\CurrentPrice;
use App\Entity\Type;
use App\Repository\CurrentPriceRepository;
use App\Repository\StationRepository;
use App\Repository\TypeRepository;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateCurrentPriceCommandHandler
{
    public function __construct(
        private StationRepository $stationRepository,
        private CurrentPriceRepository $currentPriceRepository,
        private TypeRepository $typeRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreateCurrentPriceCommand $command): void
    {
        $station = $this->stationRepository->findOneBy(['stationId' => $command->getStationId()]);

        if (null === $station) {
            return;
        }

        $type = $this->typeRepository->findOneBy(['typeId' => $command->getId()]);

        if (null === $type) {
            $type = Type::create($command->getId(), $command->getName());
            $this->typeRepository->save($type, true);
        }

        $currentPrice = CurrentPrice::create($station, $type, $command->getUpdatedAt(), $command->getValue());
        $this->currentPriceRepository->save($currentPrice, true);
    }
}
