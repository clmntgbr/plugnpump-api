<?php

namespace App\Repository;

use App\Entity\Type;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractRepository<Type>
 *
 * @method Type|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Type[]    findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Type      find($id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Type      findOneBy(array $criteria, ?array $orderBy = null)
 * @method Type[]    findAll()
 */
class TypeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Type::class);
    }
}
