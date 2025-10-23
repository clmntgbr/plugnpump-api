<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\SearchAbstract;
use Elastica\Query\Term;

class StateFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = $this->query->getQueries();

        if (isset($this->request['state']) && !empty(trim($this->request['state']))) {
            $state = trim($this->request['state']);
            $termQuery = new Term(['address.state' => $state]);
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
        return 'state';
    }
}
