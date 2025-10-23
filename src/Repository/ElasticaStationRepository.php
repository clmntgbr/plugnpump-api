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

    public function getSearchQuery(User $user, SearchInterface $search): Query
    {
        $query = parent::getSearchQuery($user, $search);
        
        $request = $search->getRequest();
        
        // Si des coordonnées géographiques sont fournies, trier par distance
        if (isset($request['latitude']) && isset($request['longitude'])) {
            $query->setSort([
                '_geo_distance' => [
                    'address.location' => (float) $request['latitude'] . ',' . (float) $request['longitude'],
                    'order' => 'asc',
                    'unit' => 'm'
                ]
            ]);
        }
        
        return $query;
    }
}
