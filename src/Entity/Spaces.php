<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Traits\IdTrait;
use App\Traits\SpaceTrait;
use App\Traits\CreateAtTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpacesRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: SpacesRepository::class)]
class Spaces
{
    use IdTrait, CreateAtTrait, SpaceTrait;
    
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
    private Collection $amenities;

    #[ORM\OneToMany(mappedBy: 'spaces', targetEntity: FavoriteSpaces::class)]
    private Collection $favorites;

    #[ORM\ManyToOne(inversedBy: 'renters')]
    private ?User $rentedByUser = null;

    #[ORM\ManyToOne(inversedBy: 'owners')]
    private ?User $ownedByUser = null;

    #[ORM\OneToMany(mappedBy: 'spaces', targetEntity: Reviews::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->createAt = new DateTimeImmutable();
        $this->status = 'free';
        $this->reference = date('Y') . '-' . uniqid();
        $this->isPublished = true;
        $this->reservations = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->type;
    }

    public function file()
    {
        return 'Archives/Spaces/' . $this->getId() . '/Galleries';
    }

    public function calculateAverageRating(): ?float
    {
        $totalRating = 0;
        $reviewCount = 0;

        foreach ($this->reviews as $review) {  
            $rating = $review->getRating();
            if ($rating !== null) { 
                $totalRating += $rating;
                $reviewCount++;
            }
        }

        if ($reviewCount === 0) {
            return null; 
        }

        return $totalRating / $reviewCount;
    }

    public function getRatingCountAndPercentage(int $targetRating): array
    {
        $totalReviews = count($this->reviews);
        $targetRatingCount = 0;
        // dd($totalReviews);
        
        foreach ($this->reviews as $review) {
            $rating = $review->getRating();
            if ($rating !== null) {
                // Arrondi de la note à l'entier le plus proche
                $roundedRating = round($rating);
                // dd($roundedRating, round($targetRating));
                if ($roundedRating === round($targetRating)) {
                    $targetRatingCount++;
                }
            }
        }

        if ($totalReviews === 0) {
            return [
                'count' => 0,
                'percentage' => 0,
            ];
        }
        // dd($targetRatingCount);
        $percentage = ($targetRatingCount / $totalReviews) * 100;
        // dd($percentage, $targetRatingCount);
        return [
            'count' => $targetRatingCount,
            'percentage' => $percentage,
        ];
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
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenty(SpaceAmenityLinks $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->setSpaces($this);
        }

        return $this;
    }

    public function removeAmenty(SpaceAmenityLinks $amenity): static
    {
        if ($this->amenities->removeElement($amenity)) {
            // set the owning side to null (unless already changed)
            if ($amenity->getSpaces() === $this) {
                $amenity->setSpaces(null);
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

    /**
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setSpaces($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getSpaces() === $this) {
                $review->setSpaces(null);
            }
        }

        return $this;
    }
}
