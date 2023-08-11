<?php

namespace App\Entity;

use App\Repository\UserConsentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserConsentRepository::class)]
class UserConsent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $consentType = null;

    #[ORM\Column]
    private ?bool $consentGiven = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $consentDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsentType(): ?string
    {
        return $this->consentType;
    }

    public function setConsentType(string $consentType): static
    {
        $this->consentType = $consentType;

        return $this;
    }

    public function isConsentGiven(): ?bool
    {
        return $this->consentGiven;
    }

    public function setConsentGiven(bool $consentGiven): static
    {
        $this->consentGiven = $consentGiven;

        return $this;
    }

    public function getConsentDate(): ?\DateTimeInterface
    {
        return $this->consentDate;
    }

    public function setConsentDate(\DateTimeInterface $consentDate): static
    {
        $this->consentDate = $consentDate;

        return $this;
    }
}
