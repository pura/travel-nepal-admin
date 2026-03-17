<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SupplierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commercial partner/supplier that can provide services (hotels, guides, transport)
 * across one or more regions in a country.
 */
#[ORM\Entity(repositoryClass: SupplierRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'supplier')]
class Supplier
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private ?string $supplierType = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $contactName = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $contactEmail = null;

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

    /** @var Collection<int, Region> */
    #[ORM\ManyToMany(targetEntity: Region::class, inversedBy: 'suppliers')]
    #[ORM\JoinTable(name: 'supplier_region')]
    private Collection $regions;

    /** @var Collection<int, Hotel> */
    #[ORM\OneToMany(targetEntity: Hotel::class, mappedBy: 'supplier')]
    private Collection $hotels;

    /** @var Collection<int, Guide> */
    #[ORM\OneToMany(targetEntity: Guide::class, mappedBy: 'supplier')]
    private Collection $guides;

    /** @var Collection<int, TransportService> */
    #[ORM\OneToMany(targetEntity: TransportService::class, mappedBy: 'supplier')]
    private Collection $transportServices;

    public function __construct()
    {
        $this->regions = new ArrayCollection();
        $this->hotels = new ArrayCollection();
        $this->guides = new ArrayCollection();
        $this->transportServices = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSupplierType(): ?string
    {
        return $this->supplierType;
    }

    public function setSupplierType(?string $supplierType): static
    {
        $this->supplierType = $supplierType;
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

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;
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

    /** @return Collection<int, Region> */
    public function getRegions(): Collection
    {
        return $this->regions;
    }

    public function addRegion(Region $region): static
    {
        if (!$this->regions->contains($region)) {
            $this->regions->add($region);
        }
        return $this;
    }

    public function removeRegion(Region $region): static
    {
        $this->regions->removeElement($region);
        return $this;
    }

    /** @return Collection<int, Hotel> */
    public function getHotels(): Collection
    {
        return $this->hotels;
    }

    /** @return Collection<int, Guide> */
    public function getGuides(): Collection
    {
        return $this->guides;
    }

    /** @return Collection<int, TransportService> */
    public function getTransportServices(): Collection
    {
        return $this->transportServices;
    }
}

