<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\SearchDecorator\Aggregation\AggregationInterface;
use App\SearchDecorator\SearchInterface;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use FOS\ElasticaBundle\Paginator\FantaPaginatorAdapter;
use FOS\ElasticaBundle\Repository;
use Pagerfanta\Pagerfanta;

class AbstractElasticaRepository extends Repository
{
    public function __construct(protected PaginatedFinderInterface $finder)
    {
    }

    /**
     * @return array{
     * "total_hits": int,
     * "items_per_page": int,
     * "current_page": int,
     * "next_page": int|null,
     * "total_pages": int,
     * "results": Pagerfanta<object>,
     * "aggregations":mixed[]|null
     * }
     */
    public function search(User $user, SearchInterface $search, int $page = 1, int $limit = 15, Query $query = null): array
    {
        if (!$query) {
            $query = $this->getSearchQuery($user, $search);
        }

        $results = $this->finder->findPaginated($query);
        $results->setMaxPerPage($limit);
        $results->setCurrentPage($page);
        $totalHits = $results->count();

        $totalPages = intval(ceil($totalHits / $limit));
        /** @var FantaPaginatorAdapter $adapter */
        $adapter = $results->getAdapter();
        $aggs = $adapter->getAggregations();

        return [
            'total_items' => $totalHits,
            'items_per_page' => $limit,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'results' => $results,
            'next_page' => ($page + 1) <= $totalPages ? $page + 1 : null,
            'aggregations' => $aggs,
        ];
    }

    public function getSearchQuery(User $user, SearchInterface $search): Query
    {
        $queries = $search->getQueries();
        $bool = new BoolQuery();

        foreach ($queries as $searchQuery) {
            if (!$searchQuery['query']) {
                continue;
            }

            foreach ($searchQuery['query'] as $q) {
                /** @var array<string, mixed> $qArray */
                $qArray = $q->toArray();

                if (
                    isset($qArray['wildcard'])
                    && is_array($qArray['wildcard'])
                    && isset($qArray['wildcard']['name'])
                    && is_array($qArray['wildcard']['name'])
                    && isset($qArray['wildcard']['name']['value'])
                ) {
                    $searchString = trim($qArray['wildcard']['name']['value']);
                    if ('' === $searchString) {
                        continue;
                    }

                    $bool = $this->getWildCards($bool, $searchString);

                    continue;
                }

                $bool->addMust($q);
            }
        }

        $optional = $search->getOptionalQueries();
        foreach ($optional as $sq) {
            foreach ($sq['query'] as $q) {
                $bool->addShould($q);
            }
        }
        if (!empty($optional)) {
            $bool->setMinimumShouldMatch(0);
        }

        $request = $search->getRequest();

        $query = $this->addAggregations(new Query(), $request);

        $query->setQuery($bool);

        if (isset($request['order'])) {
            /** @var array<string, string> $requestOrder */
            $requestOrder = $request['order'];

            return $this->sortQuery($query, $requestOrder);
        }

        return $query;
    }

    /**
     * @param array<string, string> $requestOrder
     */
    private function sortQuery(Query $query, array $requestOrder): Query
    {
        if (!empty($requestOrder)) {
            foreach ($requestOrder as $field => $order) {
                if (!in_array($field, ['startAt', 'createdAt', 'orderedAt', 'updatedAt', 'enabled'], true)) {
                    $field .= '.keyword';
                }
                $query->addSort([$field => strtolower($order)]);
            }
        } else {
            $query->setSort(['createdAt' => 'desc']);
        }

        return $query;
    }

    private function getWildCards(BoolQuery $bool, string $searchString): BoolQuery
    {
        if (false !== strpos($searchString, ' ')) {
            $tokens = preg_split('/\s+/', $searchString);
            if (false === $tokens) {
                $tokens = [];
            }

            $subBool = new BoolQuery();
            foreach ($tokens as $token) {
                if ('' !== $token) {
                    $subBool->addMust(new Query\Wildcard('name', '*'.$token.'*'));
                }
            }
            $bool->addMust($subBool);
        } else {
            $wildcardQuery = new Query\Wildcard('name', '*'.$searchString.'*');
            $bool->addMust($wildcardQuery);
        }

        return $bool;
    }

    /**
     * @param array<array<string>|float|int|string> $request
     */
    public function addAggregations(Query $query, array $request): Query
    {
        if (isset($request['aggregations']) && \is_array($request['aggregations'])) {
            foreach ($request['aggregations'] as $aggregationName) {
                $className = \sprintf('App\SearchDecorator\Aggregation\%s', $aggregationName);
                if (\class_exists($className)) {
                    /** @var AggregationInterface $aggregation */
                    $aggregation = new $className();

                    $aggregation->setAggregation($query);
                }
            }
        }

        return $query;
    }
}
