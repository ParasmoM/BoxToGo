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
    private ?string $itemCondition = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availabilityStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $availabilityEnd = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\Column]
    private ?bool $isPublished = null;

    #[ORM\ManyToOne(inversedBy: 'renter')]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'owner')]
    private ?Users $host = null;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Favoris::class)]
    private Collection $favorite;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Reservations::class)]
    private Collection $reservation;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Reports::class)]
    private Collection $report;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: SpaceImages::class, cascade: ["persist"])]
    private Collection $image;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: SpaceEquipementLink::class, cascade: ["persist"])]
    private Collection $equipment;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: SpaceTranslations::class, cascade: ["persist"])]
    private Collection $content;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Conversations::class)]
    private Collection $conversation;

    #[ORM\ManyToOne(inversedBy: 'space')]
    private ?SpaceCategories $spaceCateg = null;

    #[ORM\OneToMany(mappedBy: 'space', targetEntity: Adresses::class, cascade: ["persist"])]
    private Collection $adresse;

    public function __construct()
    {
        $this->registrationDate = new \DateTime();
        $this->status = 'free';
        $this->isPublished = true;
        $this->favorite = new ArrayCollection();
        $this->reservation = new ArrayCollection();
        $this->report = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->equipment = new ArrayCollection();
        $this->content = new ArrayCollection();
        $this->conversation = new ArrayCollection();
        $this->adresse = new ArrayCollection();
    }


    public function __toString()
    {
        return $this->spaceCateg;
    }

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

    public function getItemCondition(): ?string
    {
        return $this->itemCondition;
    }

    public function setItemCondition(?string $itemCondition): static
    {
        $this->itemCondition = $itemCondition;

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getHost(): ?Users
    {
        return $this->host;
    }

    public function setHost(?Users $host): static
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @return Collection<int, Favoris>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Favoris $favorite): static
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite->add($favorite);
            $favorite->setSpace($this);
        }

        return $this;
    }

    public function removeFavorite(Favoris $favorite): static
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getSpace() === $this) {
                $favorite->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservations>
     */
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservations $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setSpace($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSpace() === $this) {
                $reservation->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reports>
     */
    public function getReport(): Collection
    {
        return $this->report;
    }

    public function addReport(Reports $report): static
    {
        if (!$this->report->contains($report)) {
            $this->report->add($report);
            $report->setSpace($this);
        }

        return $this;
    }

    public function removeReport(Reports $report): static
    {
        if ($this->report->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getSpace() === $this) {
                $report->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SpaceImages>
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(SpaceImages $image): static
    {
        if (!$this->image->contains($image)) {
            $this->image->add($image);
            $image->setSpace($this);
        }

        return $this;
    }

    public function removeImage(SpaceImages $image): static
    {
        if ($this->image->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getSpace() === $this) {
                $image->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SpaceEquipementLink>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(SpaceEquipementLink $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
            $equipment->setSpace($this);
        }

        return $this;
    }

    public function removeEquipment(SpaceEquipementLink $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getSpace() === $this) {
                $equipment->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SpaceTranslations>
     */
    public function getContent(): Collection
    {
        return $this->content;
    }

    public function addContent(SpaceTranslations $content): static
    {
        if (!$this->content->contains($content)) {
            $this->content->add($content);
            $content->setSpace($this);
        }

        return $this;
    }

    public function removeContent(SpaceTranslations $content): static
    {
        if ($this->content->removeElement($content)) {
            // set the owning side to null (unless already changed)
            if ($content->getSpace() === $this) {
                $content->setSpace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getConversation(): Collection
    {
        return $this->conversation;
    }

    public function addConversation(Conversations $conversation): static
    {
        if (!$this->conversation->contains($conversation)) {
            $this->conversation->add($conversation);
            $conversation->setSpace($this);
        }

        return $this;
    }

    public function removeConversation(Conversations $conversation): static
    {
        if ($this->conversation->removeElement($conversation)) {
            // set the owning side to null (unless already changed)
            if ($conversation->getSpace() === $this) {
                $conversation->setSpace(null);
            }
        }

        return $this;
    }

    public function getSpaceCateg(): ?SpaceCategories
    {
        return $this->spaceCateg;
    }

    public function setSpaceCateg(?SpaceCategories $spaceCateg): static
    {
        $this->spaceCateg = $spaceCateg;

        return $this;
    }

    /**
     * @return Collection<int, Adresses>
     */
    public function getAdresse(): Collection
    {
        return $this->adresse;
    }

    public function addAdresse(Adresses $adresse): static
    {
        if (!$this->adresse->contains($adresse)) {
            $this->adresse->add($adresse);
            $adresse->setSpace($this);
        }

        return $this;
    }

    public function removeAdresse(Adresses $adresse): static
    {
        if ($this->adresse->removeElement($adresse)) {
            // set the owning side to null (unless already changed)
            if ($adresse->getSpace() === $this) {
                $adresse->setSpace(null);
            }
        }

        return $this;
    }
}
