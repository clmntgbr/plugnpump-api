<?php

declare(strict_types=1);

namespace App\Service\Data;

use App\Dto\GasStationListDto;

interface DataServiceInterface
{
    public function download(): string;

    public function extract(string $zipFilePath): string;

    public function parse(string $xmlFilePath): GasStationListDto;

    public function delete(string $filePath): void;
}
