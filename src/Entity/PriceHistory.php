<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\UuidTrait;
use App\Repository\PriceHistoryRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use ApiPlatform\Metadata\Get;

#[ORM\Entity(repositoryClass: PriceHistoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['price:read']],
        ),
    ],
)]
class PriceHistory extends Price
{
    use UuidTrait;

    #[ORM\ManyToOne(targetEntity: Station::class, inversedBy: 'priceHistories')]
    #[ORM\JoinColumn(nullable: false)]
    protected Station $station;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function create(Station $station, Type $type, \DateTime $updatedAt, float $value): self
    {
        $price = new self();
        $price->setStation($station);
        $price->setCurrency('eur');
        $price->setValue($value);
        $price->setDate($updatedAt);
        $price->setType($type);

        return $price;
    }

    #[Groups(['station:read'])]
    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getStation(): ?Station
    {
        return $this->station;
    }

    public function setStation(?Station $station): static
    {
        $this->station = $station;

        return $this;
    }
}
