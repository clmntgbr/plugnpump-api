<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\SearchAbstract;
use Elastica\Query\MatchAll;

class DefaultFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = [];

        // Requête par défaut qui retourne tous les documents
        $queries[] = [
            'query' => [new MatchAll()],
        ];

        return $queries;
    }

    public static function getName(): string
    {
        return 'default';
    }
}
