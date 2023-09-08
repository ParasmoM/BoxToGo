<?php

namespace App\Entity;

use App\Traits\IdTrait;
use App\Traits\ContentTrait;
use App\Traits\CreateAtTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TalksRepository;

#[ORM\Entity(repositoryClass: TalksRepository::class)]
class Talks
{
    use IdTrait, CreateAtTrait, ContentTrait;

    #[ORM\ManyToOne(inversedBy: 'sender')]
    private ?Users $sender = null;

    #[ORM\ManyToOne(inversedBy: 'receiver')]
    private ?Users $receiver = null;

    #[ORM\ManyToOne(inversedBy: 'talks')]
    private ?Spaces $space = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getSender(): ?Users
    {
        return $this->sender;
    }

    public function setSender(?Users $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getReceiver(): ?Users
    {
        return $this->receiver;
    }

    public function setReceiver(?Users $receiver): static
    {
        $this->receiver = $receiver;

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
}
