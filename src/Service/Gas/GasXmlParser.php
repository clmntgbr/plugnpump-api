<?php

declare(strict_types=1);

namespace App\Service\Gas;

use App\Dto\GasPriceDto;
use App\Dto\GasStationDto;
use App\Dto\GasStationListDto;

class GasXmlParser
{
    public function parseXmlFile(string $xmlFilePath): GasStationListDto
    {
        if (!file_exists($xmlFilePath)) {
            throw new \RuntimeException('XML file does not exist');
        }

        $xml = simplexml_load_file($xmlFilePath);
        if (false === $xml) {
            throw new \RuntimeException('Failed to parse XML file');
        }

        $stations = [];

        foreach ($xml->pdv as $stationXml) {
            $stations[] = $this->parseStation($stationXml);
        }

        return new GasStationListDto($stations, count($stations));
    }

    private function parseStation(\SimpleXMLElement $stationXml): GasStationDto
    {
        // Parse services
        $services = [];
        if (isset($stationXml->services)) {
            foreach ($stationXml->services->service as $service) {
                $services[] = (string) $service;
            }
        }

        // Parse prices
        $prices = [];
        foreach ($stationXml->prix as $priceXml) {
            $prices[] = new GasPriceDto(
                name: (string) $priceXml['nom'],
                id: (string) $priceXml['id'],
                updatedAt: (string) $priceXml['maj'],
                value: (float) $priceXml['valeur']
            );
        }

        return new GasStationDto(
            id: (string) $stationXml['id'],
            latitude: $this->convertCoordinate((string) $stationXml['latitude']),
            longitude: $this->convertCoordinate((string) $stationXml['longitude']),
            postalCode: (string) $stationXml['cp'],
            populationType: (string) $stationXml['pop'],
            address: (string) $stationXml->adresse,
            city: (string) $stationXml->ville,
            services: $services ?: null,
            prices: $prices ?: null,
        );
    }

    private function convertCoordinate(string $coordinate): string
    {
        // Convert coordinate from format like "4720393" to decimal format
        // The coordinates are in a special format, need to convert to standard lat/lng
        $coord = (float) $coordinate;

        // Convert to decimal degrees (this is a simplified conversion)
        // The actual conversion might need more complex logic depending on the source format
        return (string) ($coord / 100000);
    }
}
