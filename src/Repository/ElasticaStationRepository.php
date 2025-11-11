<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\SearchDecorator\SearchInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;

class ElasticaStationRepository extends AbstractElasticaRepository
{
    public function __construct(PaginatedFinderInterface $finder)
    {
        parent::__construct($finder);
    }

    public function getSearchQuery(SearchInterface $search): Query
    {
        $query = parent::getSearchQuery($search);

        $request = $search->getRequest();

        if (isset($request['latitude']) && isset($request['longitude'])) {
            $query->setSort([
                '_geo_distance' => [
                    'address.location' => (float) $request['latitude'].','.(float) $request['longitude'],
                    'order' => 'asc',
                    'unit' => 'm',
                ],
            ]);
        }

        return $query;
    }
}
