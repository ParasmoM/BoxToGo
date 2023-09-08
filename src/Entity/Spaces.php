<?php

namespace App\Entity;

use App\Repository\SpacesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpacesRepository::class)]
class Spaces
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $surface = null;

    #[ORM\Column(nullable: true)]
    private ?int $entryWidth = null;

    #[ORM\Column(nullable: true)]
    private ?int $entryLength = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $floorLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $conditionStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availabilityStartDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availabilityEndDate = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublished = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getEntryWidth(): ?int
    {
        return $this->entryWidth;
    }

    public function setEntryWidth(?int $entryWidth): static
    {
        $this->entryWidth = $entryWidth;

        return $this;
    }

    public function getEntryLength(): ?int
    {
        return $this->entryLength;
    }

    public function setEntryLength(?int $entryLength): static
    {
        $this->entryLength = $entryLength;

        return $this;
    }

    public function getFloorLevel(): ?string
    {
        return $this->floorLevel;
    }

    public function setFloorLevel(?string $floorLevel): static
    {
        $this->floorLevel = $floorLevel;

        return $this;
    }

    public function getConditionStatus(): ?string
    {
        return $this->conditionStatus;
    }

    public function setConditionStatus(?string $conditionStatus): static
    {
        $this->conditionStatus = $conditionStatus;

        return $this;
    }

    public function getAvailabilityStartDate(): ?\DateTimeInterface
    {
        return $this->availabilityStartDate;
    }

    public function setAvailabilityStartDate(?\DateTimeInterface $availabilityStartDate): static
    {
        $this->availabilityStartDate = $availabilityStartDate;

        return $this;
    }

    public function getAvailabilityEndDate(): ?\DateTimeInterface
    {
        return $this->availabilityEndDate;
    }

    public function setAvailabilityEndDate(?\DateTimeInterface $availabilityEndDate): static
    {
        $this->availabilityEndDate = $availabilityEndDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(?bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }
}
