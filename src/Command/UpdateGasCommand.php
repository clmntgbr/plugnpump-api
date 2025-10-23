<?php

namespace App\Command;

use App\Core\Application\Command\CreateOrUpdateGasStationCommand;
use App\Dto\GasStationDto;
use App\Service\Data\GasDataService;
use App\Shared\Application\Bus\CommandBusInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'update:gas',
    description: 'Update the gas prices for the stations',
    aliases: ['update:gas', 'gas:update'],
)]
class UpdateGasCommand extends Command
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
        try {
            $output->writeln('Downloading gas data...');
            $zipFilePath = $this->gasDataService->download();

            $output->writeln('Extracting gas data...');
            $xmlFilePath = $this->gasDataService->extract($zipFilePath);

            $output->writeln('Parsing gas data...');
            $gasStationList = $this->gasDataService->parse($xmlFilePath);

            $output->writeln(sprintf('Parsed %d gas stations', $gasStationList->getTotalCount()));

            $ileDeFranceStations = array_filter($gasStationList->getStations(), function (GasStationDto $station) {
                $postalCode = $station->getPostalCode();
                return preg_match('/^(75|77|78|91|92|93|94|95)\d{3}$/', $postalCode);
            });

            $output->writeln(sprintf('Found %d stations in Île-de-France', count($ileDeFranceStations)));

            array_map(function (GasStationDto $station) {
                $this->commandBus->dispatch(new CreateOrUpdateGasStationCommand($station));
            }, $ileDeFranceStations);

            $output->writeln('Gas prices updated successfully');
        } catch (\Exception $e) {
            $output->writeln(sprintf('Error: %s', $e->getMessage()));

            return Command::FAILURE;
        } finally {
            $this->gasDataService->delete($zipFilePath);
            $this->gasDataService->delete($xmlFilePath);
        }

        return Command::SUCCESS;
    }
}
