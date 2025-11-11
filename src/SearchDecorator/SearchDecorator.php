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
        Filter\ServicesFilter::class,
        Filter\CityFilter::class,
        Filter\StateFilter::class,
        Filter\PostalFilter::class,
    ];

    private SearchAbstract $search;

    public function __construct(array $request)
    {
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
