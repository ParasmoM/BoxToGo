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

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $surface = null;

    #[ORM\Column(nullable: true)]
    private ?int $entryWidth = null;

    #[ORM\Column(nullable: true)]
    private ?int $entryLength = null;

    #[ORM\Column(length: 50)]
    private ?string $floorPosition = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $condition = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $availabilityStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $availabilityEnd = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFloorPosition(): ?string
    {
        return $this->floorPosition;
    }

    public function setFloorPosition(string $floorPosition): static
    {
        $this->floorPosition = $floorPosition;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(?string $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(\DateTimeInterface $registrationDate): static
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }

    public function getAvailabilityStart(): ?\DateTimeInterface
    {
        return $this->availabilityStart;
    }

    public function setAvailabilityStart(\DateTimeInterface $availabilityStart): static
    {
        $this->availabilityStart = $availabilityStart;

        return $this;
    }

    public function getAvailabilityEnd(): ?\DateTimeInterface
    {
        return $this->availabilityEnd;
    }

    public function setAvailabilityEnd(\DateTimeInterface $availabilityEnd): static
    {
        $this->availabilityEnd = $availabilityEnd;

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

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

        return $this;
    }
}
