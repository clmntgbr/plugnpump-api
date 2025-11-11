<?php

namespace App\Command;

use App\Core\Application\Command\CreateOrUpdateGasStationCommand;
use App\Dto\GasStationDto;
use App\Service\Gas\GasDataService;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'gas:update',
    description: 'Update the gas prices for the stations',
    aliases: ['update:gas', 'gas:update'],
)]
class GasUpdateCommand extends Command
{
    public function __construct(
        private GasDataService $gasDataService,
        private CommandBusInterface $commandBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $zipFilePath = null;
        $xmlFilePath = null;
        
        try {
            $zipFilePath = $this->gasDataService->download();
            $xmlFilePath = $this->gasDataService->extract($zipFilePath);
            $gasStationList = $this->gasDataService->parse($xmlFilePath);
            
            $ileDeFranceStations = array_filter(
                $gasStationList->getStations(), 
                $this->isIleDeFranceStation(...)
            );
            
            $this->processStations($ileDeFranceStations);
            
            $output->writeln(sprintf('Updated %d stations', count($ileDeFranceStations)));
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $output->writeln(sprintf('Error: %s', $e->getMessage()));
            return Command::FAILURE;
        } finally {
            $this->cleanupTempFiles($zipFilePath, $xmlFilePath);
        }
    }

    private function isIleDeFranceStation(GasStationDto $station): bool
    {
        $postalCode = $station->getPostalCode();
        return preg_match('/^(75|77|78|91|92|93|94|95)\d{3}$/', $postalCode) === 1;
    }

    private function processStations(array $stations): void
    {
        foreach ($stations as $station) {
            $this->commandBus->dispatch(new CreateOrUpdateGasStationCommand($station));
        }
    }

    private function cleanupTempFiles(?string $zipFilePath, ?string $xmlFilePath): void
    {
        if ($zipFilePath) {
            $this->gasDataService->delete($zipFilePath);
        }
        
        if ($xmlFilePath) {
            $this->gasDataService->delete($xmlFilePath);
        }
    }
}
