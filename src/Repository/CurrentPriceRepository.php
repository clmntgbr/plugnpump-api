<?php

namespace App\Repository;

use App\Entity\CurrentPrice;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<CurrentPrice>
 *
 * @method CurrentPrice|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method CurrentPrice[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method CurrentPrice      find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method CurrentPrice      findOneBy(array $criteria, ?array $orderBy = null)
 * @method CurrentPrice[]    findAll()
 */
class CurrentPriceRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CurrentPrice::class);
    }
}
