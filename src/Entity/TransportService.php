<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TransportServiceRepository;
use App\Entity\Supplier;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ground or air transport provider (e.g. car, jeep, flight). Tracks vehicle type, capacity, base area,
 * price notes, and contact details. Used when planning transfers and transport for itinerary days.
 */
#[ORM\Entity(repositoryClass: TransportServiceRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'transport_service')]
class TransportService
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Supplier::class, inversedBy: 'transportServices')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Supplier $supplier = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $serviceType = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $vehicleType = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $baseArea = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $priceNotes = null;

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

    public function getSupplier(): ?Supplier
    {
        return $this->supplier;
    }

    public function setSupplier(?Supplier $supplier): static
    {
        $this->supplier = $supplier;
        return $this;
    }

    public function getServiceType(): ?string
    {
        return $this->serviceType;
    }

    public function setServiceType(?string $serviceType): static
    {
        $this->serviceType = $serviceType;
        return $this;
    }

    public function getVehicleType(): ?string
    {
        return $this->vehicleType;
    }

    public function setVehicleType(?string $vehicleType): static
    {
        $this->vehicleType = $vehicleType;
        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(?int $capacity): static
    {
        $this->capacity = $capacity;
        return $this;
    }

    public function getBaseArea(): ?string
    {
        return $this->baseArea;
    }

    public function setBaseArea(?string $baseArea): static
    {
        $this->baseArea = $baseArea;
        return $this;
    }

    public function getPriceNotes(): ?string
    {
        return $this->priceNotes;
    }

    public function setPriceNotes(?string $priceNotes): static
    {
        $this->priceNotes = $priceNotes;
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
