<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Dto\GasStationDto;
use App\Entity\Trait\UuidTrait;
use App\Repository\AddressRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['address:read']],
        ),
        new Get(
            normalizationContext: ['groups' => ['address:read']],
        ),
    ],
)]
#[ORM\Table()]
#[ORM\Index(columns: ['postal_code'], name: 'idx_postal_code')]
#[ORM\Index(columns: ['city'], name: 'idx_city')]
#[ORM\Index(columns: ['country'], name: 'idx_country')]
class Address
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 255)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $streetLine1 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $streetLine2 = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Assert\Length(max: 255)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $streetLine3 = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 100)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    #[Assert\Length(max: 100)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $state = null;

    #[ORM\Column(type: Types::STRING, length: 20)]
    #[Assert\NotBlank()]
    #[Assert\Length(max: 20)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $postalCode = null;

    #[ORM\Column(type: Types::STRING, length: 2)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 2)]
    #[Assert\Country]
    #[Groups(['station:search', 'address:read'])]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    #[Assert\Range(min: -90, max: 90)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 8, nullable: true)]
    #[Assert\Range(min: -180, max: 180)]
    #[Groups(['station:search', 'address:read'])]
    private ?string $longitude = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000)]
    #[Groups(['address:read'])]
    private ?string $additionalInfo = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public static function create(GasStationDto $gasStation): self
    {
        $address = new self();
        $address->setStreetLine1($gasStation->getAddress());
        $address->setCity($gasStation->getCity());
        $address->setPostalCode($gasStation->getPostalCode());
        $address->setCountry('fr');
        $address->setLatitude($gasStation->getLatitude());
        $address->setLongitude($gasStation->getLongitude());

        return $address;
    }

    public function getStreetLine1(): ?string
    {
        return $this->streetLine1;
    }

    public function setStreetLine1(string $streetLine1): static
    {
        $this->streetLine1 = $streetLine1;

        return $this;
    }

    public function getStreetLine2(): ?string
    {
        return $this->streetLine2;
    }

    public function setStreetLine2(?string $streetLine2): static
    {
        $this->streetLine2 = $streetLine2;

        return $this;
    }

    public function getStreetLine3(): ?string
    {
        return $this->streetLine3;
    }

    public function setStreetLine3(?string $streetLine3): static
    {
        $this->streetLine3 = $streetLine3;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): static
    {
        $this->state = $state;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = strtoupper($country);

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAdditionalInfo(): ?string
    {
        return $this->additionalInfo;
    }

    public function setAdditionalInfo(?string $additionalInfo): static
    {
        $this->additionalInfo = $additionalInfo;

        return $this;
    }

    public function getLocation(): ?string
    {
        if (null !== $this->latitude && null !== $this->longitude) {
            return $this->latitude.','.$this->longitude;
        }

        return null;
    }
}
