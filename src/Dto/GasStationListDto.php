<?php

declare(strict_types=1);

namespace App\Dto;

class GasStationListDto
{
    /**
     * @param GasStationDto[] $stations
     */
    public function __construct(
        private readonly array $stations,
        private readonly int $totalCount,
    ) {
    }

    public function getStations(): array
    {
        return $this->stations;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}
