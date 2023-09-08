<?php

namespace App\Entity;

use App\Repository\UserConsentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserConsentRepository::class)]
class UserConsent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $consentType = null;

    #[ORM\Column(nullable: true)]
    private ?bool $consentGiven = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\ManyToOne(inversedBy: 'consents')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsentType(): ?string
    {
        return $this->consentType;
    }

    public function setConsentType(?string $consentType): static
    {
        $this->consentType = $consentType;

        return $this;
    }

    public function isConsentGiven(): ?bool
    {
        return $this->consentGiven;
    }

    public function setConsentGiven(?bool $consentGiven): static
    {
        $this->consentGiven = $consentGiven;

        return $this;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
