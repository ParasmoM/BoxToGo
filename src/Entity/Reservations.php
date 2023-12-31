<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReservationsRepository;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $dateStart = null;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private ?\DateTimeImmutable $dateEnd = null;

    #[ORM\Column(length: 255)]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $price = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Spaces $space = null;

    #[ORM\OneToOne(inversedBy: 'reservations', cascade: ['persist', 'remove'])]
    private ?Payments $payment = null;

    public function __construct($space)
    {
        $this->createAt = new \DateTimeImmutable();
        $this->reference = 'R-' . date('Y') . '-' . uniqid();

        $this->space = $space;
        $this->price = $space->getPrice();
        $this->status = $space->getStatus();
    }

    public function updateStatusBasedOnDate(): void
    {
        $currentDate = new DateTimeImmutable();
        if ($currentDate > $this->dateEnd) {
            $this->status = 'finished';
        }
    }

    public function getDateDifference(): ?array
    {
        if ($this->dateStart && $this->dateEnd) {
            // Calculer la différence entre dateEnd et dateStart
            $interval = $this->dateStart->diff($this->dateEnd);

            $years = $interval->y;
            $months = $interval->m;
            $days = $interval->d;

            $difference = [];

            if ($years > 0) {
                $difference[] = "$years année" . ($years > 1 ? 's' : '');
            }
            if ($months > 0) {
                $difference[] = "$months mois";
            }
            if ($days > 0) {
                $difference[] = "$days jour" . ($days > 1 ? 's' : '');
            }

            return $difference;
        }

        return null; // Retourner null si l'une des dates est nulle
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

    public function getDateStart(): ?\DateTimeImmutable
    {
        return $this->dateStart;
    }

    public function setDateStart(\DateTimeImmutable $dateStart): static
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function getDateEnd(): ?\DateTimeImmutable
    {
        return $this->dateEnd;
    }

    public function setDateEnd(\DateTimeImmutable $dateEnd): static
    {
        $this->dateEnd = $dateEnd;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
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
}
