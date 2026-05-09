<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ItineraryTemplateDayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * A single day within an itinerary template. Describes the day's title, description, optional destination,
 * suggested hotel category, transport and guide types, and activity notes. Belongs to one ItineraryTemplate.
 */
#[ORM\Entity(repositoryClass: ItineraryTemplateDayRepository::class)]
#[ORM\Table(name: 'itinerary_template_day')]
class ItineraryTemplateDay
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: ItineraryTemplate::class, inversedBy: 'days')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ItineraryTemplate $itineraryTemplate = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $dayNumber = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $details = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $meals = null;

    /**
     * Display name for public export (e.g. "Kathmandu"). If empty, the linked Destination name is used when set.
     */
    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $destinationName = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $distanceKm = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $altitudeMaxM = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $altitudeMinM = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $durationHours = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $accommodation = null;

    #[ORM\ManyToOne(targetEntity: Destination::class, inversedBy: 'itineraryTemplateDays')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Destination $destination = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $hotelCategory = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $transportType = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $guideType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $activityNotes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getItineraryTemplate(): ?ItineraryTemplate
    {
        return $this->itineraryTemplate;
    }

    public function setItineraryTemplate(?ItineraryTemplate $itineraryTemplate): static
    {
        $this->itineraryTemplate = $itineraryTemplate;
        return $this;
    }

    public function getDayNumber(): ?int
    {
        return $this->dayNumber;
    }

    public function setDayNumber(int $dayNumber): static
    {
        $this->dayNumber = $dayNumber;
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;
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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(?string $details): static
    {
        $this->details = $details;
        return $this;
    }

    public function getMeals(): ?string
    {
        return $this->meals;
    }

    public function setMeals(?string $meals): static
    {
        $this->meals = $meals;
        return $this;
    }

    public function getDestinationName(): ?string
    {
        return $this->destinationName;
    }

    public function setDestinationName(?string $destinationName): static
    {
        $this->destinationName = $destinationName;
        return $this;
    }

    public function getDistanceKm(): ?int
    {
        return $this->distanceKm;
    }

    public function setDistanceKm(?int $distanceKm): static
    {
        $this->distanceKm = $distanceKm;
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

    public function getDurationHours(): ?float
    {
        return $this->durationHours;
    }

    public function setDurationHours(?float $durationHours): static
    {
        $this->durationHours = $durationHours;
        return $this;
    }

    public function getAccommodation(): ?string
    {
        return $this->accommodation;
    }

    public function setAccommodation(?string $accommodation): static
    {
        $this->accommodation = $accommodation;
        return $this;
    }

    public function getDestination(): ?Destination
    {
        return $this->destination;
    }

    public function setDestination(?Destination $destination): static
    {
        $this->destination = $destination;
        return $this;
    }

    public function getHotelCategory(): ?string
    {
        return $this->hotelCategory;
    }

    public function setHotelCategory(?string $hotelCategory): static
    {
        $this->hotelCategory = $hotelCategory;
        return $this;
    }

    public function getTransportType(): ?string
    {
        return $this->transportType;
    }

    public function setTransportType(?string $transportType): static
    {
        $this->transportType = $transportType;
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

    public function getActivityNotes(): ?string
    {
        return $this->activityNotes;
    }

    public function setActivityNotes(?string $activityNotes): static
    {
        $this->activityNotes = $activityNotes;
        return $this;
    }
}
