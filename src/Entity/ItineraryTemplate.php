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

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $interestTags = [];

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

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

    public function getComfortLevel(): ?string
    {
        return $this->comfortLevel;
    }

    public function setComfortLevel(?string $comfortLevel): static
    {
        $this->comfortLevel = $comfortLevel;
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

    /** @return array<int, string> */
    public function getInterestTags(): array
    {
        return $this->interestTags;
    }

    /** @param array<int, string> $interestTags */
    public function setInterestTags(array $interestTags): static
    {
        $this->interestTags = $interestTags;
        return $this;
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
