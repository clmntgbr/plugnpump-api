<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\SearchAbstract;
use Elastica\Query\Term;

class PostalFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = $this->query->getQueries();

        if (isset($this->request['postal']) && !empty(trim($this->request['postal']))) {
            $postal = trim($this->request['postal']);
            $termQuery = new Term(['address.postalCode' => $postal]);
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
        return 'postal';
    }
}
