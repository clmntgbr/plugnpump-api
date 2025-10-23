<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Address>
 *
 * @method Address|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Address[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Address      find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Address      findOneBy(array $criteria, ?array $orderBy = null)
 * @method Address[]    findAll()
 */
class AddressRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }
}
