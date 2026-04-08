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
    public const SERVICE_AIRPORT_TRANSFER = 'airport_transfer';
    public const SERVICE_CITY_TRANSFER = 'city_transfer';
    public const SERVICE_INTERCITY_TRANSFER = 'intercity_transfer';
    public const SERVICE_SIGHTSEEING = 'sightseeing';
    public const SERVICE_TREK_DROP_PICKUP = 'trek_drop_pickup';
    public const SERVICE_JEEP_HIRE = 'jeep_hire';
    public const SERVICE_MOUNTAIN_FLIGHT = 'mountain_flight';
    public const SERVICE_HELICOPTER_CHARTER = 'helicopter_charter';

    public const VEHICLE_HATCHBACK_CAR = 'hatchback_car';
    public const VEHICLE_SEDAN_CAR = 'sedan_car';
    public const VEHICLE_SUV = 'suv';
    public const VEHICLE_4WD_JEEP = '4wd_jeep';
    public const VEHICLE_VAN_HIACE = 'van_hiace';
    public const VEHICLE_MINIBUS = 'minibus';
    public const VEHICLE_TOURIST_BUS = 'tourist_bus';
    public const VEHICLE_DOMESTIC_FLIGHT = 'domestic_flight';
    public const VEHICLE_HELICOPTER = 'helicopter';
    public const VEHICLE_MOTORBIKE = 'motorbike';

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

    /** @return array<string, string> */
    public static function getServiceTypeChoices(): array
    {
        return [
            'Airport Transfer' => self::SERVICE_AIRPORT_TRANSFER,
            'City Transfer' => self::SERVICE_CITY_TRANSFER,
            'Intercity Transfer' => self::SERVICE_INTERCITY_TRANSFER,
            'Sightseeing Tour Transport' => self::SERVICE_SIGHTSEEING,
            'Trek Drop/Pickup' => self::SERVICE_TREK_DROP_PICKUP,
            'Jeep Hire' => self::SERVICE_JEEP_HIRE,
            'Mountain Flight' => self::SERVICE_MOUNTAIN_FLIGHT,
            'Helicopter Charter' => self::SERVICE_HELICOPTER_CHARTER,
        ];
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

    /** @return array<string, string> */
    public static function getVehicleTypeChoices(): array
    {
        return [
            'Hatchback Car' => self::VEHICLE_HATCHBACK_CAR,
            'Sedan Car' => self::VEHICLE_SEDAN_CAR,
            'SUV' => self::VEHICLE_SUV,
            '4WD Jeep' => self::VEHICLE_4WD_JEEP,
            'Van (Hiace)' => self::VEHICLE_VAN_HIACE,
            'Minibus' => self::VEHICLE_MINIBUS,
            'Tourist Bus' => self::VEHICLE_TOURIST_BUS,
            'Domestic Flight' => self::VEHICLE_DOMESTIC_FLIGHT,
            'Helicopter' => self::VEHICLE_HELICOPTER,
            'Motorbike' => self::VEHICLE_MOTORBIKE,
        ];
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
