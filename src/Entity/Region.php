<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Geographical region within a country (e.g. Everest Region, Annapurna, Kathmandu,
 * Pokhara, Deurali, etc. Where you might find a hotel, tea house, guide, transport service, etc.).
 * Used to describe where suppliers operate. This might or might not a destination, but a region within a country.
 * A region can have many suppliers, and a supplier can be in many regions.
 * These regions will be part of itineries for tours and trips, to get to a destination.
 */
#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'region')]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $isActive = true;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $updatedAt = null;

    /** @var Collection<int, Supplier> */
    #[ORM\ManyToMany(targetEntity: Supplier::class, mappedBy: 'regions')]
    private Collection $suppliers;

    /** @var Collection<int, Hotel> */
    #[ORM\OneToMany(targetEntity: Hotel::class, mappedBy: 'region')]
    private Collection $hotels;

    /** @var Collection<int, ItineraryTemplate> */
    #[ORM\OneToMany(targetEntity: ItineraryTemplate::class, mappedBy: 'startingRegion')]
    private Collection $startingItineraryTemplates;

    public function __construct()
    {
        $this->suppliers = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->startingItineraryTemplates = new ArrayCollection();
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

    public function __toString(): string
    {
        return (string) ($this->name ?? '');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
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

    /** @return Collection<int, Supplier> */
    public function getSuppliers(): Collection
    {
        return $this->suppliers;
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
            $hotel->setRegion($this);
        }

        return $this;
    }

    public function removeHotel(Hotel $hotel): static
    {
        if ($this->hotels->removeElement($hotel)) {
            if ($hotel->getRegion() === $this) {
                $hotel->setRegion(null);
            }
        }

        return $this;
    }

    /** @return Collection<int, ItineraryTemplate> */
    public function getStartingItineraryTemplates(): Collection
    {
        return $this->startingItineraryTemplates;
    }
}

