<?php

namespace App\Entity;

use App\Repository\SpacesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'space')]
    private ?SpaceTypes $type = null;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Reservations::class)]
    private Collection $reservations;

    #[ORM\OneToOne(inversedBy: 'spaces', cascade: ['persist', 'remove'])]
    private ?Addresses $adresse = null;

    #[ORM\OneToMany(mappedBy: 'spaces', targetEntity: Images::class)]
    private Collection $image;

    #[ORM\OneToOne(inversedBy: 'spaces', cascade: ['persist', 'remove'])]
    private ?Contents $content = null;

    #[ORM\OneToMany(mappedBy: 'spaces', targetEntity: SpaceAmenityLinks::class)]
    private Collection $amenties;

    #[ORM\OneToMany(mappedBy: 'spaces', targetEntity: FavoriteSpaces::class)]
    private Collection $favorites;

    #[ORM\ManyToOne(inversedBy: 'renters')]
    private ?User $rentedByUser = null;

    #[ORM\ManyToOne(inversedBy: 'owners')]
    private ?User $ownedByUser = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->amenties = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

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

    public function getType(): ?SpaceTypes
    {
        return $this->type;
    }

    public function setType(?SpaceTypes $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservations $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSpace($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSpace() === $this) {
                $reservation->setSpace(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?Addresses
    {
        return $this->adresse;
    }

    public function setAdresse(?Addresses $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Images $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setSpaces($this);
        }

        return $this;
    }

    public function removeImage(Images $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getSpaces() === $this) {
                $image->setSpaces(null);
            }
        }

        return $this;
    }

    public function getContent(): ?Contents
    {
        return $this->content;
    }

    public function setContent(?Contents $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, SpaceAmenityLinks>
     */
    public function getAmenties(): Collection
    {
        return $this->amenties;
    }

    public function addAmenty(SpaceAmenityLinks $amenty): static
    {
        if (!$this->amenties->contains($amenty)) {
            $this->amenties->add($amenty);
            $amenty->setSpaces($this);
        }

        return $this;
    }

    public function removeAmenty(SpaceAmenityLinks $amenty): static
    {
        if ($this->amenties->removeElement($amenty)) {
            // set the owning side to null (unless already changed)
            if ($amenty->getSpaces() === $this) {
                $amenty->setSpaces(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoriteSpaces>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(FavoriteSpaces $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setSpaces($this);
        }

        return $this;
    }

    public function removeFavorite(FavoriteSpaces $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getSpaces() === $this) {
                $favorite->setSpaces(null);
            }
        }

        return $this;
    }

    public function getRentedByUser(): ?User
    {
        return $this->rentedByUser;
    }

    public function setRentedByUser(?User $rentedByUser): static
    {
        $this->rentedByUser = $rentedByUser;

        return $this;
    }

    public function getOwnedByUser(): ?User
    {
        return $this->ownedByUser;
    }

    public function setOwnedByUser(?User $ownedByUser): static
    {
        $this->ownedByUser = $ownedByUser;

        return $this;
    }
}
