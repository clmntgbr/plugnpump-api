<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\GeoDistanceQuery;
use App\SearchDecorator\Query\SearchAbstract;

class GeoDistanceFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = $this->query->getQueries();

        if (isset($this->request['latitude']) && isset($this->request['longitude'])
            && (true === $this->request['geo_distance'] || 'true' === $this->request['geo_distance'])) {
            $latitude = (float) $this->request['latitude'];
            $longitude = (float) $this->request['longitude'];
            $distance = (int) ($this->request['distance'] ?? 100000);

            $geoQuery = new GeoDistanceQuery($latitude, $longitude, $distance);
            $this->elasticaRequest = [$geoQuery];

            $queries[$this->getName()] = [
                'value' => $this->request[$this->getName()] ?? null,
                'query' => $this->elasticaRequest,
            ];
        }

        return $queries;
    }

    public static function getName(): string
    {
        return 'geo_distance';
    }
}
