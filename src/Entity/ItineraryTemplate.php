<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ItineraryTemplateRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reusable trip blueprint (e.g. "7-day Classic Nepal", "Everest Trek"). Defines trip type, duration,
 * budget/comfort/difficulty levels, interest tags, a summary, and optional starting region. Composed of one or more
 * ItineraryTemplateDay records for day-by-day planning.
 */
#[ORM\Entity(repositoryClass: ItineraryTemplateRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'itinerary_template')]
class ItineraryTemplate
{
    public const TRIP_TYPE_CULTURAL_HERITAGE = 'cultural_heritage';
    public const TRIP_TYPE_TREKKING_HIKING = 'trekking_hiking';
    public const TRIP_TYPE_ADVENTURE = 'adventure';
    public const TRIP_TYPE_WILDLIFE_JUNGLE = 'wildlife_jungle';
    public const TRIP_TYPE_SPIRITUAL_PILGRIMAGE = 'spiritual_pilgrimage';
    public const TRIP_TYPE_LUXURY_LEISURE = 'luxury_leisure';
    public const TRIP_TYPE_FAMILY = 'family';

    public const COMFORT_BASIC = 'basic';
    public const COMFORT_STANDARD = 'standard';
    public const COMFORT_PREMIUM = 'premium';

    public const BUDGET_BUDGET = 'budget';
    public const BUDGET_MID_RANGE = 'mid_range';
    public const BUDGET_PREMIUM = 'premium';

    public const DIFFICULTY_EASY = 'easy';
    public const DIFFICULTY_MODERATE = 'moderate';
    public const DIFFICULTY_CHALLENGING = 'challenging';

    public const INTEREST_MOUNTAINS = 'mountains';
    public const INTEREST_TREKKING = 'trekking';
    public const INTEREST_CULTURE = 'culture';
    public const INTEREST_HERITAGE = 'heritage';
    public const INTEREST_WILDLIFE = 'wildlife';
    public const INTEREST_PHOTOGRAPHY = 'photography';
    public const INTEREST_ADVENTURE_SPORTS = 'adventure_sports';
    public const INTEREST_FOOD = 'food';
    public const INTEREST_SPIRITUAL = 'spiritual';
    public const INTEREST_FESTIVALS = 'festivals';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\ManyToOne(targetEntity: Region::class, inversedBy: 'startingItineraryTemplates')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Region $startingRegion = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $tripType = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $durationDays = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $budgetLevel = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $comfortLevel = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $difficultyLevel = null;

    /** @var array<int, string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $interestTags = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::STRING, length: 32, nullable: true)]
    private ?string $priceAmount = null;

    #[ORM\Column(type: Types::STRING, length: 8, nullable: true)]
    private ?string $priceCurrency = null;

    /** Full image URL (e.g. CDN or Unsplash); takes precedence over heroImagePath in export when set. */
    #[ORM\Column(type: Types::STRING, length: 2048, nullable: true)]
    private ?string $heroImageUrl = null;

    /** Path relative to /public (e.g. uploads/itinerary-hero/uuid.jpg) when using admin upload. */
    #[ORM\Column(type: Types::STRING, length: 512, nullable: true)]
    private ?string $heroImagePath = null;

    /** @var list<string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $galleryImageUrls = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $totalDistanceKm = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $altitudeMaxM = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $altitudeMinM = null;

    /** @var list<string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $servicesIncluded = null;

    /** @var list<string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $servicesExcluded = null;

    /** @var list<string>|null */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $servicesOptional = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /** @var Collection<int, ItineraryTemplateDay> */
    #[ORM\OneToMany(targetEntity: ItineraryTemplateDay::class, mappedBy: 'itineraryTemplate', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['dayNumber' => 'ASC'])]
    private Collection $days;

    public function __construct()
    {
        $this->days = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }

    public function getStartingRegion(): ?Region
    {
        return $this->startingRegion;
    }

    public function setStartingRegion(?Region $startingRegion): static
    {
        $this->startingRegion = $startingRegion;
        return $this;
    }

    public function getTripType(): ?string
    {
        return $this->tripType;
    }

    public function setTripType(?string $tripType): static
    {
        $this->tripType = $tripType;
        return $this;
    }

    /** @return array<string, string> */
    public static function getTripTypeChoices(): array
    {
        return [
            'Cultural & Heritage' => self::TRIP_TYPE_CULTURAL_HERITAGE,
            'Trekking & Hiking' => self::TRIP_TYPE_TREKKING_HIKING,
            'Adventure' => self::TRIP_TYPE_ADVENTURE,
            'Wildlife & Jungle' => self::TRIP_TYPE_WILDLIFE_JUNGLE,
            'Spiritual/Pilgrimage' => self::TRIP_TYPE_SPIRITUAL_PILGRIMAGE,
            'Luxury & Leisure' => self::TRIP_TYPE_LUXURY_LEISURE,
            'Family' => self::TRIP_TYPE_FAMILY,
        ];
    }

    public function getDurationDays(): ?int
    {
        return $this->durationDays;
    }

    public function setDurationDays(?int $durationDays): static
    {
        $this->durationDays = $durationDays;
        return $this;
    }

    public function getBudgetLevel(): ?string
    {
        return $this->budgetLevel;
    }

    public function setBudgetLevel(?string $budgetLevel): static
    {
        $this->budgetLevel = $budgetLevel;
        return $this;
    }

    /** @return array<string, string> */
    public static function getBudgetLevelChoices(): array
    {
        return [
            'Budget' => self::BUDGET_BUDGET,
            'Mid-range' => self::BUDGET_MID_RANGE,
            'Premium' => self::BUDGET_PREMIUM,
        ];
    }

    public function getComfortLevel(): ?string
    {
        return $this->comfortLevel;
    }

    public function setComfortLevel(?string $comfortLevel): static
    {
        $this->comfortLevel = $comfortLevel;
        return $this;
    }

    /** @return array<string, string> */
    public static function getComfortLevelChoices(): array
    {
        return [
            'Basic' => self::COMFORT_BASIC,
            'Standard' => self::COMFORT_STANDARD,
            'Premium' => self::COMFORT_PREMIUM,
        ];
    }

    public function getDifficultyLevel(): ?string
    {
        return $this->difficultyLevel;
    }

    public function setDifficultyLevel(?string $difficultyLevel): static
    {
        $this->difficultyLevel = $difficultyLevel;
        return $this;
    }

    /** @return array<string, string> */
    public static function getDifficultyLevelChoices(): array
    {
        return [
            'Easy' => self::DIFFICULTY_EASY,
            'Moderate' => self::DIFFICULTY_MODERATE,
            'Challenging' => self::DIFFICULTY_CHALLENGING,
        ];
    }

    /** @return array<int, string> */
    public function getInterestTags(): array
    {
        return $this->interestTags ?? [];
    }

    /** @param array<int, string>|null $interestTags */
    public function setInterestTags(?array $interestTags): static
    {
        $this->interestTags = $interestTags;

        return $this;
    }

    /** @return array<string, string> */
    public static function getInterestTagChoices(): array
    {
        return [
            'Mountains' => self::INTEREST_MOUNTAINS,
            'Trekking' => self::INTEREST_TREKKING,
            'Culture' => self::INTEREST_CULTURE,
            'Heritage' => self::INTEREST_HERITAGE,
            'Wildlife' => self::INTEREST_WILDLIFE,
            'Photography' => self::INTEREST_PHOTOGRAPHY,
            'Adventure Sports' => self::INTEREST_ADVENTURE_SPORTS,
            'Food' => self::INTEREST_FOOD,
            'Spiritual' => self::INTEREST_SPIRITUAL,
            'Festivals' => self::INTEREST_FESTIVALS,
        ];
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): static
    {
        $this->summary = $summary;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getPriceAmount(): ?string
    {
        return $this->priceAmount;
    }

    public function setPriceAmount(?string $priceAmount): static
    {
        $this->priceAmount = $priceAmount;
        return $this;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }

    public function setPriceCurrency(?string $priceCurrency): static
    {
        $this->priceCurrency = $priceCurrency;
        return $this;
    }

    public function getHeroImageUrl(): ?string
    {
        return $this->heroImageUrl;
    }

    public function setHeroImageUrl(?string $heroImageUrl): static
    {
        $this->heroImageUrl = $heroImageUrl;
        return $this;
    }

    public function getHeroImagePath(): ?string
    {
        return $this->heroImagePath;
    }

    public function setHeroImagePath(?string $heroImagePath): static
    {
        $this->heroImagePath = $heroImagePath;
        return $this;
    }

    /** @return list<string> */
    public function getGalleryImageUrls(): array
    {
        return $this->galleryImageUrls ?? [];
    }

    /** @param list<string>|null $galleryImageUrls */
    public function setGalleryImageUrls(?array $galleryImageUrls): static
    {
        $this->galleryImageUrls = $galleryImageUrls;
        return $this;
    }

    public function getTotalDistanceKm(): ?int
    {
        return $this->totalDistanceKm;
    }

    public function setTotalDistanceKm(?int $totalDistanceKm): static
    {
        $this->totalDistanceKm = $totalDistanceKm;
        return $this;
    }

    public function getAltitudeMaxM(): ?int
    {
        return $this->altitudeMaxM;
    }

    public function setAltitudeMaxM(?int $altitudeMaxM): static
    {
        $this->altitudeMaxM = $altitudeMaxM;
        return $this;
    }

    public function getAltitudeMinM(): ?int
    {
        return $this->altitudeMinM;
    }

    public function setAltitudeMinM(?int $altitudeMinM): static
    {
        $this->altitudeMinM = $altitudeMinM;
        return $this;
    }

    /** @return list<string> */
    public function getServicesIncluded(): array
    {
        return $this->servicesIncluded ?? [];
    }

    /** @param list<string>|null $servicesIncluded */
    public function setServicesIncluded(?array $servicesIncluded): static
    {
        $this->servicesIncluded = $servicesIncluded;
        return $this;
    }

    /** @return list<string> */
    public function getServicesExcluded(): array
    {
        return $this->servicesExcluded ?? [];
    }

    /** @param list<string>|null $servicesExcluded */
    public function setServicesExcluded(?array $servicesExcluded): static
    {
        $this->servicesExcluded = $servicesExcluded;
        return $this;
    }

    /** @return list<string> */
    public function getServicesOptional(): array
    {
        return $this->servicesOptional ?? [];
    }

    /** @param list<string>|null $servicesOptional */
    public function setServicesOptional(?array $servicesOptional): static
    {
        $this->servicesOptional = $servicesOptional;
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

    /** @return Collection<int, ItineraryTemplateDay> */
    public function getDays(): Collection
    {
        return $this->days;
    }

    public function addDay(ItineraryTemplateDay $day): static
    {
        if (!$this->days->contains($day)) {
            $this->days->add($day);
            $day->setItineraryTemplate($this);
        }
        return $this;
    }

    public function removeDay(ItineraryTemplateDay $day): static
    {
        if ($this->days->removeElement($day)) {
            if ($day->getItineraryTemplate() === $this) {
                $day->setItineraryTemplate(null);
            }
        }
        return $this;
    }
}
