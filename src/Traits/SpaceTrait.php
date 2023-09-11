<?php

namespace App\Traits;

trait SpaceTrait {
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