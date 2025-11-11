<?php

declare(strict_types=1);

namespace App\Core\Application\CommandHandler;

use App\Core\Application\Command\CreateCurrentPriceCommand;
use App\Core\Application\Command\CreateOrUpdateGasPriceCommand;
use App\Core\Application\Command\CreatePriceHistoryCommand;
use App\Repository\CurrentPriceRepository;
use App\Repository\StationRepository;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateOrUpdateGasPriceCommandHandler
{
    public function __construct(
        private StationRepository $stationRepository,
        private CurrentPriceRepository $currentPriceRepository,
        private CommandBusInterface $commandBus,
    ) {
    }

    public function __invoke(CreateOrUpdateGasPriceCommand $command): void
    {
        $station = $this->stationRepository->findOneBy(['stationId' => $command->getStationId()]);

        if (null === $station) {
            return;
        }

        $currentPrice = $station->getCurrentPriceByTypeId($command->getGasPrice()->getId());

        if (null === $currentPrice) {
            $this->createCurrentPrice($command);
            $this->createPriceHistory($command);
            return;
        }

        $newUpdatedAt = new \DateTime($command->getGasPrice()->getUpdatedAt());
        $currentDate = $currentPrice->getDate();
        $newValue = $command->getGasPrice()->getValue();
        $currentValue = $currentPrice->getValue();

        // Si la date n'est pas plus récente, on ne fait rien
        if ($newUpdatedAt <= $currentDate) {
            return;
        }

        // La date est plus récente et le prix est différent
        if ($currentValue !== $newValue) {
            $this->currentPriceRepository->delete($currentPrice);
            $this->createCurrentPrice($command);
            $this->createPriceHistory($command);
            return;
        }

        // La date est plus récente mais le prix est le même
        $currentPrice->setDate($newUpdatedAt);
        $this->currentPriceRepository->save($currentPrice);
    }

    private function createCurrentPrice(CreateOrUpdateGasPriceCommand $command): void
    {
        $this->commandBus->dispatch(new CreateCurrentPriceCommand(
            $command->getStationId(),
            $command->getGasPrice()->getName(),
            $command->getGasPrice()->getId(),
            new \DateTime($command->getGasPrice()->getUpdatedAt()),
            $command->getGasPrice()->getValue()
        ));
    }

    private function createPriceHistory(CreateOrUpdateGasPriceCommand $command): void
    {
        $this->commandBus->dispatch(new CreatePriceHistoryCommand(
            $command->getStationId(),
            $command->getGasPrice()->getName(),
            $command->getGasPrice()->getId(),
            new \DateTime($command->getGasPrice()->getUpdatedAt()),
            $command->getGasPrice()->getValue()
        ));
    }
}
