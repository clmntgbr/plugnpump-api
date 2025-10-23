<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Entity\Trait\UuidTrait;
use App\Repository\GooglePlaceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GooglePlaceRepository::class)]
#[ApiResource]
class GooglePlace
{
    use UuidTrait;
    use TimestampableEntity;

    #[ORM\Column(type: Types::STRING, length: 255)]
    #[Groups(['station:read'])]
    private string $placeId;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['station:read'])]
    private ?string $internationalPhoneNumber = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['station:read'])]
    private ?float $rating = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    #[Groups(['station:read'])]
    private ?float $userRatingCount = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    #[Groups(['station:read'])]
    private string $businessStatus;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['station:read'])]
    private ?string $websiteUri = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['station:read'])]
    private ?string $googleMapsDirectionsUri = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['station:read'])]
    private ?string $googleMapsPlaceUri = null;

    public function getPlaceId(): string
    {
        return $this->placeId;
    }

    public function setPlaceId(string $placeId): self
    {
        $this->placeId = $placeId;

        return $this;
    }

    public function getInternationalPhoneNumber(): ?string
    {
        return $this->internationalPhoneNumber;
    }

    public function setInternationalPhoneNumber(?string $internationalPhoneNumber): self
    {
        $this->internationalPhoneNumber = $internationalPhoneNumber;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(?float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getUserRatingCount(): ?float
    {
        return $this->userRatingCount;
    }

    public function setUserRatingCount(?float $userRatingCount): self
    {
        $this->userRatingCount = $userRatingCount;

        return $this;
    }

    public function getBusinessStatus(): string
    {
        return $this->businessStatus;
    }

    public function setBusinessStatus(string $businessStatus): self
    {
        $this->businessStatus = $businessStatus;

        return $this;
    }

    public function getWebsiteUri(): ?string
    {
        return $this->websiteUri;
    }

    public function setWebsiteUri(?string $websiteUri): self
    {
        $this->websiteUri = $websiteUri;

        return $this;
    }

    public function getGoogleMapsDirectionsUri(): ?string
    {
        return $this->googleMapsDirectionsUri;
    }

    public function setGoogleMapsDirectionsUri(?string $googleMapsDirectionsUri): self
    {
        $this->googleMapsDirectionsUri = $googleMapsDirectionsUri;

        return $this;
    }

    public function getGoogleMapsPlaceUri(): ?string
    {
        return $this->googleMapsPlaceUri;
    }

    public function setGoogleMapsPlaceUri(?string $googleMapsPlaceUri): self
    {
        $this->googleMapsPlaceUri = $googleMapsPlaceUri;

        return $this;
    }
}
