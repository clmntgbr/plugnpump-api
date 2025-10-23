<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Dto\GasStationDto;
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
    private string $stationId;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['station:search', 'station:read'])]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['station:search', 'station:read'])]
    private array $services = [];

    #[ORM\OneToOne(targetEntity: Address::class, cascade: ['persist', 'remove'])]
    #[Groups(['station:search', 'station:read'])]
    private Address $address;

    public static function create(GasStationDto $gasStation): self
    {
        $station = new self();
        $station->setStationId($gasStation->getStationId());
        $station->setName($gasStation->getAddress());
        $station->setAddress(Address::create($gasStation));

        return $station;
    }

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->services = [];
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

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getStationId(): string
    {
        return $this->stationId;
    }

    public function setStationId(string $stationId): self
    {
        $this->stationId = $stationId;

        return $this;
    }

    public function getServices(): array
    {
        return $this->services;
    }

    public function setServices(array $services): self
    {
        $this->services = $services;

        return $this;
    }
}
