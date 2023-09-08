<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(length: 255)]
    private ?string $givenName = null;

    #[ORM\Column(length: 255)]
    private ?string $familyName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $language = null;

    #[ORM\Column(length: 255)]
    private ?string $appearance = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reservations::class)]
    private Collection $reservations;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Addresses $adresse = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Images $image = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Contents $content = null;

    #[ORM\OneToMany(mappedBy: 'rentedByUser', targetEntity: Spaces::class)]
    private Collection $renters;

    #[ORM\OneToMany(mappedBy: 'ownedByUser', targetEntity: Spaces::class)]
    private Collection $owners;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payments::class)]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'sentByUser', targetEntity: Conversations::class)]
    private Collection $sentConversations;

    #[ORM\OneToMany(mappedBy: 'receivedByUser', targetEntity: Conversations::class)]
    private Collection $receivedConversations;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reviews::class)]
    private Collection $reviews;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FavoriteSpaces::class)]
    private Collection $favorites;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserConsent::class)]
    private Collection $consents;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->renters = new ArrayCollection();
        $this->owners = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->sentConversations = new ArrayCollection();
        $this->receivedConversations = new ArrayCollection();
        $this->reviews = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->consents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    public function setGivenName(string $givenName): static
    {
        $this->givenName = $givenName;

        return $this;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(string $familyName): static
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): static
    {
        $this->picture = $picture;

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

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getAppearance(): ?string
    {
        return $this->appearance;
    }

    public function setAppearance(string $appearance): static
    {
        $this->appearance = $appearance;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): static
    {
        $this->googleId = $googleId;

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
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
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

    public function getImage(): ?Images
    {
        return $this->image;
    }

    public function setImage(?Images $image): static
    {
        $this->image = $image;

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
     * @return Collection<int, Spaces>
     */
    public function getRenters(): Collection
    {
        return $this->renters;
    }

    public function addRenter(Spaces $renter): static
    {
        if (!$this->renters->contains($renter)) {
            $this->renters->add($renter);
            $renter->setRentedByUser($this);
        }

        return $this;
    }

    public function removeRenter(Spaces $renter): static
    {
        if ($this->renters->removeElement($renter)) {
            // set the owning side to null (unless already changed)
            if ($renter->getRentedByUser() === $this) {
                $renter->setRentedByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spaces>
     */
    public function getOwners(): Collection
    {
        return $this->owners;
    }

    public function addOwner(Spaces $owner): static
    {
        if (!$this->owners->contains($owner)) {
            $this->owners->add($owner);
            $owner->setOwnedByUser($this);
        }

        return $this;
    }

    public function removeOwner(Spaces $owner): static
    {
        if ($this->owners->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getOwnedByUser() === $this) {
                $owner->setOwnedByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payments>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payments $payment): static
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setUser($this);
        }

        return $this;
    }

    public function removePayment(Payments $payment): static
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUser() === $this) {
                $payment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getSentConversations(): Collection
    {
        return $this->sentConversations;
    }

    public function addSentConversation(Conversations $sentConversation): static
    {
        if (!$this->sentConversations->contains($sentConversation)) {
            $this->sentConversations->add($sentConversation);
            $sentConversation->setSentByUser($this);
        }

        return $this;
    }

    public function removeSentConversation(Conversations $sentConversation): static
    {
        if ($this->sentConversations->removeElement($sentConversation)) {
            // set the owning side to null (unless already changed)
            if ($sentConversation->getSentByUser() === $this) {
                $sentConversation->setSentByUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getReceivedConversations(): Collection
    {
        return $this->receivedConversations;
    }

    public function addReceivedConversation(Conversations $receivedConversation): static
    {
        if (!$this->receivedConversations->contains($receivedConversation)) {
            $this->receivedConversations->add($receivedConversation);
            $receivedConversation->setReceivedByUser($this);
        }

        return $this;
    }

    public function removeReceivedConversation(Conversations $receivedConversation): static
    {
        if ($this->receivedConversations->removeElement($receivedConversation)) {
            // set the owning side to null (unless already changed)
            if ($receivedConversation->getReceivedByUser() === $this) {
                $receivedConversation->setReceivedByUser(null);
            }
        }

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
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
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
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(FavoriteSpaces $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserConsent>
     */
    public function getConsents(): Collection
    {
        return $this->consents;
    }

    public function addConsent(UserConsent $consent): static
    {
        if (!$this->consents->contains($consent)) {
            $this->consents->add($consent);
            $consent->setUser($this);
        }

        return $this;
    }

    public function removeConsent(UserConsent $consent): static
    {
        if ($this->consents->removeElement($consent)) {
            // set the owning side to null (unless already changed)
            if ($consent->getUser() === $this) {
                $consent->setUser(null);
            }
        }

        return $this;
    }
}
