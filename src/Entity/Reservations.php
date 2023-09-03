<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?Users $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?Spaces $space = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 20)]
    private ?string $status = null;

    #[ORM\OneToOne(inversedBy: 'reservation', cascade: ['persist', 'remove'])]
    private ?Payments $payment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $stripeToken = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $brandStripe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $last4Stripe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $idChargeStripe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statusStripe = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateEnd = null;

    public function __construct($space)
    {
        $this->createdAt = new \DateTime();
        $this->reference = 'R-' . date('Y') . '-' . uniqid();
        $this->status = 'busy';
    }
    
    public function updateStatusBasedOnDate(): void
    {
        $currentDate = new \DateTime();
        if ($currentDate > $this->dateEnd) {
            $this->status = 'finished';
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateStart(): ?\DateTimeInterface
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeInterface $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeInterface
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeInterface $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

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

    public function getReservationDate(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setReservationDate(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSpace(): ?Spaces
    {
        return $this->space;
    }

    public function setSpace(?Spaces $space): static
    {
        $this->space = $space;

        return $this;
    }

    public function getPayment(): ?Payments
    {
        return $this->payment;
    }

    public function setPayment(?Payments $payment): static
    {
        $this->payment = $payment;

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

    public function getBrandStripe(): ?string
    {
        return $this->brandStripe;
    }

    public function setBrandStripe(?string $brandStripe): static
    {
        $this->brandStripe = $brandStripe;

        return $this;
    }

    public function getLast4Stripe(): ?string
    {
        return $this->last4Stripe;
    }

    public function setLast4Stripe(?string $last4Stripe): static
    {
        $this->last4Stripe = $last4Stripe;

        return $this;
    }

    public function getIdChargeStripe(): ?string
    {
        return $this->idChargeStripe;
    }

    public function setIdChargeStripe(?string $idChargeStripe): static
    {
        $this->idChargeStripe = $idChargeStripe;

        return $this;
    }

    public function getStatusStripe(): ?string
    {
        return $this->statusStripe;
    }

    public function setStatusStripe(?string $statusStripe): static
    {
        $this->statusStripe = $statusStripe;

        return $this;
    }
}
