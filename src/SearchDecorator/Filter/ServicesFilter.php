<?php

declare(strict_types=1);

namespace App\SearchDecorator\Filter;

use App\SearchDecorator\Query\SearchAbstract;
use Elastica\Query\Terms;

class ServicesFilter extends SearchAbstract
{
    public function getQueries(): array
    {
        $queries = $this->query->getQueries();

        if (isset($this->request['services']) && is_array($this->request['services']) && !empty($this->request['services'])) {
            $services = array_filter($this->request['services'], fn ($service) => !empty(trim($service)));

            if (!empty($services)) {
                $termsQuery = new Terms('services', $services);
                $this->elasticaRequest = [$termsQuery];

                $queries[$this->getName()] = [
                    'value' => $this->request[$this->getName()] ?? null,
                    'query' => $this->elasticaRequest,
                ];
            }
        }

        return $queries;
    }

    public static function getName(): string
    {
        return 'services';
    }
}
