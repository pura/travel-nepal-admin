<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * A place or region in Nepal that can be offered as part of a trip (e.g. Kathmandu, Pokhara, Everest Base Camp).
 * Stores descriptions, suggested duration, budget/difficulty levels, activity tags, and best months to visit.
 */
#[ORM\Entity(repositoryClass: DestinationRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'destination')]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $longDescription = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $region = null;

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $activityTags = [];

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $bestMonths = [];

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $minDays = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $maxDays = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $budgetLevel = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $difficultyLevel = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /** @var Collection<int, Hotel> */
    #[ORM\OneToMany(targetEntity: Hotel::class, mappedBy: 'destination')]
    private Collection $hotels;

    /** @var Collection<int, ItineraryTemplateDay> */
    #[ORM\OneToMany(targetEntity: ItineraryTemplateDay::class, mappedBy: 'destination')]
    private Collection $itineraryTemplateDays;

    public function __construct()
    {
        $this->hotels = new ArrayCollection();
        $this->itineraryTemplateDays = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;
        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(?string $longDescription): static
    {
        $this->longDescription = $longDescription;
        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): static
    {
        $this->region = $region;
        return $this;
    }

    /** @return array<int, string> */
    public function getActivityTags(): array
    {
        return $this->activityTags;
    }

    /** @param array<int, string> $activityTags */
    public function setActivityTags(array $activityTags): static
    {
        $this->activityTags = $activityTags;
        return $this;
    }

    /** @return array<int, string> */
    public function getBestMonths(): array
    {
        return $this->bestMonths;
    }

    /** @param array<int, string> $bestMonths */
    public function setBestMonths(array $bestMonths): static
    {
        $this->bestMonths = $bestMonths;
        return $this;
    }

    public function getMinDays(): ?int
    {
        return $this->minDays;
    }

    public function setMinDays(?int $minDays): static
    {
        $this->minDays = $minDays;
        return $this;
    }

    public function getMaxDays(): ?int
    {
        return $this->maxDays;
    }

    public function setMaxDays(?int $maxDays): static
    {
        $this->maxDays = $maxDays;
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

    public function getDifficultyLevel(): ?string
    {
        return $this->difficultyLevel;
    }

    public function setDifficultyLevel(?string $difficultyLevel): static
    {
        $this->difficultyLevel = $difficultyLevel;
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

    /** @return Collection<int, Hotel> */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    public function addHotel(Hotel $hotel): static
    {
        if (!$this->hotels->contains($hotel)) {
            $this->hotels->add($hotel);
            $hotel->setDestination($this);
        }
        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        if ($this->hotels->removeElement($hotel)) {
            if ($hotel->getDestination() === $this) {
                $hotel->setDestination(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, ItineraryTemplateDay> */
    public function getItineraryTemplateDays(): Collection
    {
        return $this->itineraryTemplateDays;
    }
}
