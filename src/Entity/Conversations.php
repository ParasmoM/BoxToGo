<?php

namespace App\Entity;

use App\Repository\ConversationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversationsRepository::class)]
class Conversations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'sentConversations')]
    private ?User $sentByUser = null;

    #[ORM\ManyToOne(inversedBy: 'receivedConversations')]
    private ?User $receivedByUser = null;

    public function __construct()
    {
        $this->createAt = new \DateTimeImmutable();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getSentByUser(): ?User
    {
        return $this->sentByUser;
    }

    public function setSentByUser(?User $sentByUser): static
    {
        $this->sentByUser = $sentByUser;

        return $this;
    }

    public function getReceivedByUser(): ?User
    {
        return $this->receivedByUser;
    }

    public function setReceivedByUser(?User $receivedByUser): static
    {
        $this->receivedByUser = $receivedByUser;

        return $this;
    }
}
