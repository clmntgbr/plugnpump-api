<?php

declare(strict_types=1);

namespace App\SearchDecorator;

use App\SearchDecorator\Query\SearchAbstract;

class SearchDecorator
{
    /**
     * @var array|string[]
     */
    private array $searchQueries = [
        Filter\GeoDistanceFilter::class,
    ];

    private SearchAbstract $search;

    public function __construct(array $request)
    {
        // Activer automatiquement le filtre géographique si latitude et longitude sont présentes
        // et que geo_distance n'est pas explicitement défini à false
        if (isset($request['latitude']) && isset($request['longitude']) && !isset($request['geo_distance'])) {
            $request['geo_distance'] = true;
        }

        $this->search = new Query\Search($request);

        foreach ($this->searchQueries as $q) {
            if (isset($request[$q::getName()])) {
                $this->search = new $q($this->search);
            }
        }
    }

    /**
     * @return SearchInterface
     */
    public function getSearch()
    {
        return $this->search;
    }
}
