<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\GeoDistanceQuery;
use App\SearchDecorator\Query\SearchAbstract;

class GeoDistanceFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = [];
        
        if (isset($this->request['latitude']) && isset($this->request['longitude'])) {
            $latitude = (float) $this->request['latitude'];
            $longitude = (float) $this->request['longitude'];
            $distance = (int) ($this->request['distance'] ?? 100000);
            
            $queries[] = [
                'query' => [new GeoDistanceQuery($latitude, $longitude, $distance)]
            ];
        }
        
        return $queries;
    }

    public static function getName(): string
    {
        return 'geo_distance';
    }
}
