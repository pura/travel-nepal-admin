<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GuideRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * A tour guide available for hire. Stores languages, regions supported, daily rate range, specialties,
 * certifications, and contact information. Used when planning itinerary days or assigning guides to trips.
 */
#[ORM\Entity(repositoryClass: GuideRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'guide')]
class Guide
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $guideType = null;

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $languages = [];

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $regionsSupported = [];

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $dailyRateFrom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $dailyRateTo = null;

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $specialties = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $certificationNotes = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $contactName = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getGuideType(): ?string
    {
        return $this->guideType;
    }

    public function setGuideType(?string $guideType): static
    {
        $this->guideType = $guideType;
        return $this;
    }

    /** @return array<int, string> */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /** @param array<int, string> $languages */
    public function setLanguages(array $languages): static
    {
        $this->languages = $languages;
        return $this;
    }

    /** @return array<int, string> */
    public function getRegionsSupported(): array
    {
        return $this->regionsSupported;
    }

    /** @param array<int, string> $regionsSupported */
    public function setRegionsSupported(array $regionsSupported): static
    {
        $this->regionsSupported = $regionsSupported;
        return $this;
    }

    public function getDailyRateFrom(): ?string
    {
        return $this->dailyRateFrom;
    }

    public function setDailyRateFrom(?string $dailyRateFrom): static
    {
        $this->dailyRateFrom = $dailyRateFrom;
        return $this;
    }

    public function getDailyRateTo(): ?string
    {
        return $this->dailyRateTo;
    }

    public function setDailyRateTo(?string $dailyRateTo): static
    {
        $this->dailyRateTo = $dailyRateTo;
        return $this;
    }

    /** @return array<int, string> */
    public function getSpecialties(): array
    {
        return $this->specialties;
    }

    /** @param array<int, string> $specialties */
    public function setSpecialties(array $specialties): static
    {
        $this->specialties = $specialties;
        return $this;
    }

    public function getCertificationNotes(): ?string
    {
        return $this->certificationNotes;
    }

    public function setCertificationNotes(?string $certificationNotes): static
    {
        $this->certificationNotes = $certificationNotes;
        return $this;
    }

    public function getContactName(): ?string
    {
        return $this->contactName;
    }

    public function setContactName(?string $contactName): static
    {
        $this->contactName = $contactName;
        return $this;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): static
    {
        $this->contactPhone = $contactPhone;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
