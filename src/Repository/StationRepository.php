<?php

namespace App\Repository;

use App\Entity\Station;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Station>
 *
 * @method Station|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Station[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Station      find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Station      findOneBy(array $criteria, ?array $orderBy = null)
 * @method Station[]    findAll()
 */
class StationRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }
}
