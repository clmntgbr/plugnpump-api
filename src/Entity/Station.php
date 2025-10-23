<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\UuidTrait;
use App\Repository\StationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StationRepository::class)]
#[ApiResource]
class Station
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['station:read'])]
    private string $name;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
