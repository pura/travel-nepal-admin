<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\HotelRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Accommodation option in a geographical region. Tracks name, category, price range, amenities, and contact details
 * for booking. Linked to one Region.
 */
#[ORM\Entity(repositoryClass: HotelRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'hotel')]
class Hotel
{
    public const CATEGORY_BUDGET = 'budget';
    public const CATEGORY_STANDARD = 'standard';
    public const CATEGORY_BOUTIQUE = 'boutique';
    public const CATEGORY_LUXURY = 'luxury';
    public const CATEGORY_RESORT = 'resort';
    public const CATEGORY_HOMESTAY = 'homestay';
    public const CATEGORY_GUESTHOUSE = 'guesthouse';
    public const CATEGORY_HOSTEL = 'hostel';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'hotels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[ORM\ManyToOne(targetEntity: Supplier::class, inversedBy: 'hotels')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Supplier $supplier = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $nightlyPriceFrom = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2, nullable: true)]
    private ?string $nightlyPriceTo = null;

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $amenities = [];

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $contactName = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $contactPhone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;
        return $this;
    }

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;
        return $this;
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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;
        return $this;
    }

    /** @return array<string, string> */
    public static function getCategoryChoices(): array
    {
        return [
            'Budget Hotel' => self::CATEGORY_BUDGET,
            'Standard Hotel' => self::CATEGORY_STANDARD,
            'Boutique Hotel' => self::CATEGORY_BOUTIQUE,
            'Luxury Hotel' => self::CATEGORY_LUXURY,
            'Resort' => self::CATEGORY_RESORT,
            'Homestay' => self::CATEGORY_HOMESTAY,
            'Guesthouse' => self::CATEGORY_GUESTHOUSE,
            'Hostel' => self::CATEGORY_HOSTEL,
        ];
    }

    public function getNightlyPriceFrom(): ?string
    {
        return $this->nightlyPriceFrom;
    }

    public function setNightlyPriceFrom(?string $nightlyPriceFrom): static
    {
        $this->nightlyPriceFrom = $nightlyPriceFrom;
        return $this;
    }

    public function getNightlyPriceTo(): ?string
    {
        return $this->nightlyPriceTo;
    }

    public function setNightlyPriceTo(?string $nightlyPriceTo): static
    {
        $this->nightlyPriceTo = $nightlyPriceTo;
        return $this;
    }

    /** @return array<int, string> */
    public function getAmenities(): array
    {
        return $this->amenities;
    }

    /** @param array<int, string> $amenities */
    public function setAmenities(array $amenities): static
    {
        $this->amenities = $amenities;
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

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;
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
