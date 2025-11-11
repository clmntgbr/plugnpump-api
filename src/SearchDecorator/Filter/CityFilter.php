<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\SearchAbstract;
use Elastica\Query\Term;

class CityFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = $this->query->getQueries();

        if (isset($this->request['city']) && !empty(trim($this->request['city']))) {
            $city = trim($this->request['city']);
            $termQuery = new Term(['address.city' => $city]);
            $this->elasticaRequest = [$termQuery];

            $queries[$this->getName()] = [
                'value' => $this->request[$this->getName()] ?? null,
                'query' => $this->elasticaRequest,
            ];
        }

        return $queries;
    }

    public static function getName(): string
    {
        return 'city';
    }
}
