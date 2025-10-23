<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Entity\Trait\UuidTrait;
use App\Repository\TypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['type:read']],
        ),
    ],
)]
class Type
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['type:read'])]
    private string $name;

    #[ORM\Column(type: Types::STRING)]
    #[Groups(['type:read'])]
    private string $typeId;

    public static function create(string $typeId, string $name): self
    {
        $type = new self();
        $type->id = Uuid::v4();
        $type->name = $name;
        $type->typeId = $typeId;

        return $type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeId(): string
    {
        return $this->typeId;
    }
}
