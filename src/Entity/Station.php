<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\GasStationDto;
use App\Entity\Trait\UuidTrait;
use App\Repository\StationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['station:read', 'address:read', 'price:read', 'type:read']],
        ),
    ],
)]
class Station
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['station:read', 'station:search'])]
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

    #[ORM\OneToOne(targetEntity: GooglePlace::class, cascade: ['persist', 'remove'])]
    #[Groups(['station:search', 'station:read'])]
    private ?GooglePlace $googlePlace = null;

    #[ORM\OneToMany(targetEntity: CurrentPrice::class, mappedBy: 'station')]
    #[ORM\OrderBy(['date' => 'DESC'])]
    #[Groups(['price:read', 'station:read'])]
    private Collection $currentPrices;

    #[ORM\OneToMany(targetEntity: PriceHistory::class, mappedBy: 'station')]
    #[ORM\OrderBy(['date' => 'DESC'])]
    #[Groups(['price:read', 'station:read'])]
    private Collection $priceHistories;

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
        $this->currentPrices = new ArrayCollection();
        $this->priceHistories = new ArrayCollection();
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

    public function getCurrentPriceByTypeId(string $typeId): ?CurrentPrice
    {
        $currentPrice = $this->currentPrices->filter(fn (CurrentPrice $currentPrice) => (string) $currentPrice->getType()->getTypeId() === $typeId)->first();
        if (false === $currentPrice) {
            return null;
        }

        return $currentPrice;
    }

    public function getPriceHistories(): Collection
    {
        return $this->priceHistories;
    }

    public function getCurrentPrices(): Collection
    {
        return $this->currentPrices;
    }
}
