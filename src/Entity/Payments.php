<?php

namespace App\Entity;

use App\Repository\PaymentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentsRepository::class)]
class Payments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payment')]
    private ?Users $user = null;

    #[ORM\OneToOne(mappedBy: 'payment', cascade: ['persist', 'remove'])]
    private ?Reservations $reservation = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 20)]
    private ?string $method = null;

    #[ORM\Column(length: 255)]
    private ?string $stripeChargeId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeBrand = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeLast4 = null;

    #[ORM\Column(length: 20)]
    private ?string $stripeStatus = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->reference = 'P-' . date('Y') . '-' . uniqid();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStripeStatus(): ?string
    {
        return $this->stripeStatus;
    }

    public function setStripeStatus(string $stripeStatus): static
    {
        $this->stripeStatus = $stripeStatus;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getStripeChargeId(): ?string
    {
        return $this->stripeChargeId;
    }

    public function setStripeChargeId(string $stripeChargeId): static
    {
        $this->stripeChargeId = $stripeChargeId;

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

    public function getReservation(): ?Reservations
    {
        return $this->reservation;
    }

    public function setReservation(?Reservations $reservation): static
    {
        // unset the owning side of the relation if necessary
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setPayment(null);
        }

        // set the owning side of the relation if necessary
        if ($reservation !== null && $reservation->getPayment() !== $this) {
            $reservation->setPayment($this);
        }

        $this->reservation = $reservation;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStripeToken(): ?string
    {
        return $this->stripeToken;
    }

    public function setStripeToken(?string $stripeToken): static
    {
        $this->stripeToken = $stripeToken;

        return $this;
    }

    public function getStripeBrand(): ?string
    {
        return $this->stripeBrand;
    }

    public function setStripeBrand(?string $stripeBrand): static
    {
        $this->stripeBrand = $stripeBrand;

        return $this;
    }

    public function getStripeLast4(): ?string
    {
        return $this->stripeLast4;
    }

    public function setStripeLast4(?string $stripeLast4): static
    {
        $this->stripeLast4 = $stripeLast4;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
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
}
