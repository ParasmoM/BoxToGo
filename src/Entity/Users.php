<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
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

    #[ORM\Column(length: 50)]
    private ?string $givenName = null;

    #[ORM\Column(length: 50)]
    private ?string $familyName = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $profilePicture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registrationDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 20)]
    private ?string $language = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $googleId = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reservations::class)]
    private Collection $reservation;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Messages::class)]
    private Collection $message;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Adresses $adresse = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Favoris::class)]
    private Collection $favorite;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SpaceReviews::class)]
    private Collection $review;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notifications::class)]
    private Collection $alert;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Payments::class)]
    private Collection $payment;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserConsent::class)]
    private Collection $consent;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Admin $admin = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reports::class)]
    private Collection $reportingUser;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Reports::class)]
    private Collection $reportedUser;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Spaces::class)]
    private Collection $renter;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Spaces::class)]
    private Collection $owner;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conversations::class)]
    private Collection $conversationUser;

    #[ORM\OneToMany(mappedBy: 'host', targetEntity: Conversations::class)]
    private Collection $conversationHost;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    public function __construct()
    {
        $this->registrationDate = new \DateTime();
        $this->language = 'EN';
        $this->reservation = new ArrayCollection();
        $this->message = new ArrayCollection();
        $this->favorite = new ArrayCollection();
        $this->review = new ArrayCollection();
        $this->alert = new ArrayCollection();
        $this->payment = new ArrayCollection();
        $this->consent = new ArrayCollection();
        $this->reportingUser = new ArrayCollection();
        $this->reportedUser = new ArrayCollection();
        $this->renter = new ArrayCollection();
        $this->owner = new ArrayCollection();
        $this->conversationUser = new ArrayCollection();
        $this->conversationHost = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->givenName . ' ' . $this->familyNameName;
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

    public function setBirthDate(\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture): static
    {
        $this->profilePicture = $profilePicture;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
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
    public function getReservation(): Collection
    {
        return $this->reservation;
    }

    public function addReservation(Reservations $reservation): static
    {
        if (!$this->reservation->contains($reservation)) {
            $this->reservation->add($reservation);
            $reservation->setUser($this);
        }

        return $this;
    }

    public function removeReservation(Reservations $reservation): static
    {
        if ($this->reservation->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getUser() === $this) {
                $reservation->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Messages>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Messages $message): static
    {
        if (!$this->message->contains($message)) {
            $this->message->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Messages $message): static
    {
        if ($this->message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    public function getAdresse(): ?Adresses
    {
        return $this->adresse;
    }

    public function setAdresse(?Adresses $adresse): static
    {
        $this->adresse = $adresse;

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
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favoris $favorite): static
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SpaceReviews>
     */
    public function getReview(): Collection
    {
        return $this->review;
    }

    public function addReview(SpaceReviews $review): static
    {
        if (!$this->review->contains($review)) {
            $this->review->add($review);
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(SpaceReviews $review): static
    {
        if ($this->review->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getAlert(): Collection
    {
        return $this->alert;
    }

    public function addAlert(Notifications $alert): static
    {
        if (!$this->alert->contains($alert)) {
            $this->alert->add($alert);
            $alert->setUser($this);
        }

        return $this;
    }

    public function removeAlert(Notifications $alert): static
    {
        if ($this->alert->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getUser() === $this) {
                $alert->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Payments>
     */
    public function getPayment(): Collection
    {
        return $this->payment;
    }

    public function addPayment(Payments $payment): static
    {
        if (!$this->payment->contains($payment)) {
            $this->payment->add($payment);
            $payment->setUser($this);
        }

        return $this;
    }

    public function removePayment(Payments $payment): static
    {
        if ($this->payment->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUser() === $this) {
                $payment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserConsent>
     */
    public function getConsent(): Collection
    {
        return $this->consent;
    }

    public function addConsent(UserConsent $consent): static
    {
        if (!$this->consent->contains($consent)) {
            $this->consent->add($consent);
            $consent->setUser($this);
        }

        return $this;
    }

    public function removeConsent(UserConsent $consent): static
    {
        if ($this->consent->removeElement($consent)) {
            // set the owning side to null (unless already changed)
            if ($consent->getUser() === $this) {
                $consent->setUser(null);
            }
        }

        return $this;
    }

    public function getAdmin(): ?Admin
    {
        return $this->admin;
    }

    public function setAdmin(?Admin $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, Reports>
     */
    public function getReportingUser(): Collection
    {
        return $this->reportingUser;
    }

    public function addReportingUser(Reports $reportingUser): static
    {
        if (!$this->reportingUser->contains($reportingUser)) {
            $this->reportingUser->add($reportingUser);
            $reportingUser->setUser($this);
        }

        return $this;
    }

    public function removeReportingUser(Reports $reportingUser): static
    {
        if ($this->reportingUser->removeElement($reportingUser)) {
            // set the owning side to null (unless already changed)
            if ($reportingUser->getUser() === $this) {
                $reportingUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reports>
     */
    public function getReportedUser(): Collection
    {
        return $this->reportedUser;
    }

    public function addReportedUser(Reports $reportedUser): static
    {
        if (!$this->reportedUser->contains($reportedUser)) {
            $this->reportedUser->add($reportedUser);
            $reportedUser->setHost($this);
        }

        return $this;
    }

    public function removeReportedUser(Reports $reportedUser): static
    {
        if ($this->reportedUser->removeElement($reportedUser)) {
            // set the owning side to null (unless already changed)
            if ($reportedUser->getHost() === $this) {
                $reportedUser->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spaces>
     */
    public function getRenter(): Collection
    {
        return $this->renter;
    }

    public function addRenter(Spaces $renter): static
    {
        if (!$this->renter->contains($renter)) {
            $this->renter->add($renter);
            $renter->setUser($this);
        }

        return $this;
    }

    public function removeRenter(Spaces $renter): static
    {
        if ($this->renter->removeElement($renter)) {
            // set the owning side to null (unless already changed)
            if ($renter->getUser() === $this) {
                $renter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Spaces>
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(Spaces $owner): static
    {
        if (!$this->owner->contains($owner)) {
            $this->owner->add($owner);
            $owner->setHost($this);
        }

        return $this;
    }

    public function removeOwner(Spaces $owner): static
    {
        if ($this->owner->removeElement($owner)) {
            // set the owning side to null (unless already changed)
            if ($owner->getHost() === $this) {
                $owner->setHost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getConversationUser(): Collection
    {
        return $this->conversationUser;
    }

    public function addConversationUser(Conversations $conversationUser): static
    {
        if (!$this->conversationUser->contains($conversationUser)) {
            $this->conversationUser->add($conversationUser);
            $conversationUser->setUser($this);
        }

        return $this;
    }

    public function removeConversationUser(Conversations $conversationUser): static
    {
        if ($this->conversationUser->removeElement($conversationUser)) {
            // set the owning side to null (unless already changed)
            if ($conversationUser->getUser() === $this) {
                $conversationUser->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Conversations>
     */
    public function getConversationHost(): Collection
    {
        return $this->conversationHost;
    }

    public function addConversationHost(Conversations $conversationHost): static
    {
        if (!$this->conversationHost->contains($conversationHost)) {
            $this->conversationHost->add($conversationHost);
            $conversationHost->setHost($this);
        }

        return $this;
    }

    public function removeConversationHost(Conversations $conversationHost): static
    {
        if ($this->conversationHost->removeElement($conversationHost)) {
            // set the owning side to null (unless already changed)
            if ($conversationHost->getHost() === $this) {
                $conversationHost->setHost(null);
            }
        }

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }
}
