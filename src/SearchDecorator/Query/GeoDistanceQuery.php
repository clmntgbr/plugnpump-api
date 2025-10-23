<?php

declare(strict_types=1);

namespace App\SearchDecorator\Query;

use Elastica\Query\AbstractQuery;

class GeoDistanceQuery extends AbstractQuery
{
    public function __construct(
        private float $latitude,
        private float $longitude,
        private int $distance = 100000,
    ) {
    }

    public function toArray(): array
    {
        return [
            'geo_distance' => [
                'distance' => $this->distance.'m',
                'address.location' => $this->latitude.','.$this->longitude,
            ],
        ];
    }
}
