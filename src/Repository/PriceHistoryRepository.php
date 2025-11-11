<?php

namespace App\Repository;

use App\Entity\PriceHistory;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<PriceHistory>
 *
 * @method PriceHistory|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method PriceHistory[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method PriceHistory      find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method PriceHistory      findOneBy(array $criteria, ?array $orderBy = null)
 * @method PriceHistory[]    findAll()
 */
class PriceHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PriceHistory::class);
    }
}
