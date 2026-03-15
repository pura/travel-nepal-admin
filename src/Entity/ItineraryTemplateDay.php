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
