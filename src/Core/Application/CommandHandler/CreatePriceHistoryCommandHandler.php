<?php

declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Core\Application\Command\CreateCurrentPriceCommand;
use App\Core\Application\Command\CreateOrUpdateGasPriceCommand;
use App\Core\Application\Command\CreatePriceHistoryCommand;
use App\Entity\PriceHistory;
use App\Entity\Type;
use App\Repository\CurrentPriceRepository;
use App\Repository\PriceHistoryRepository;
use App\Repository\StationRepository;
use App\Repository\TypeRepository;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreatePriceHistoryCommandHandler
{
    public function __construct(
        private StationRepository $stationRepository,
        private CurrentPriceRepository $currentPriceRepository,
        private TypeRepository $typeRepository,
        private PriceHistoryRepository $priceHistoryRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreatePriceHistoryCommand $command): void
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

        $priceHistory = PriceHistory::create($station, $type, $command->getUpdatedAt(), $command->getValue());
        $this->priceHistoryRepository->save($priceHistory, true);
    }
}
