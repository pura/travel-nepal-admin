<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RepresentativeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Company or agency representative (e.g. sales, support). Stores contact channels (email, phone, WhatsApp),
 * languages spoken, and active hours. Used for assigning points of contact to trips or inquiries.
 */
#[ORM\Entity(repositoryClass: RepresentativeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'representative')]
class Representative
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true)]
    private ?string $whatsapp = null;

    /** @var array<int, string> */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $languages = [];

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $activeHours = null;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): static
    {
        $this->whatsapp = $whatsapp;
        return $this;
    }

    /** @return array<int, string> */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /** @param array<int, string> $languages */
    public function setLanguages(array $languages): static
    {
        $this->languages = $languages;
        return $this;
    }

    public function getActiveHours(): ?string
    {
        return $this->activeHours;
    }

    public function setActiveHours(?string $activeHours): static
    {
        $this->activeHours = $activeHours;
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
