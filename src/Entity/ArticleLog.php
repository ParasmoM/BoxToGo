<?php

namespace App\Entity;

use App\Repository\ArticleLogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleLogRepository::class)]
class ArticleLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $action = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $actionDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reasonForDeletion = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function getActionDate(): ?\DateTimeInterface
    {
        return $this->actionDate;
    }

    public function setActionDate(\DateTimeInterface $actionDate): static
    {
        $this->actionDate = $actionDate;

        return $this;
    }

    public function getReasonForDeletion(): ?string
    {
        return $this->reasonForDeletion;
    }

    public function setReasonForDeletion(?string $reasonForDeletion): static
    {
        $this->reasonForDeletion = $reasonForDeletion;

        return $this;
    }
}
