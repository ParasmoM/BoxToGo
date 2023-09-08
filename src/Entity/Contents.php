<?php

namespace App\Entity;

use App\Repository\ContentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentsRepository::class)]
class Contents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_fr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_en = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title_ne = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_fr = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_en = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_ne = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?Spaces $spaces = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleFr(): ?string
    {
        return $this->title_fr;
    }

    public function setTitleFr(?string $title_fr): static
    {
        $this->title_fr = $title_fr;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->title_en;
    }

    public function setTitleEn(?string $title_en): static
    {
        $this->title_en = $title_en;

        return $this;
    }

    public function getTitleNe(): ?string
    {
        return $this->title_ne;
    }

    public function setTitleNe(?string $title_ne): static
    {
        $this->title_ne = $title_ne;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->description_fr;
    }

    public function setDescriptionFr(?string $description_fr): static
    {
        $this->description_fr = $description_fr;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->description_en;
    }

    public function setDescriptionEn(?string $description_en): static
    {
        $this->description_en = $description_en;

        return $this;
    }

    public function getDescriptionNe(): ?string
    {
        return $this->description_ne;
    }

    public function setDescriptionNe(?string $description_ne): static
    {
        $this->description_ne = $description_ne;

        return $this;
    }

    public function getSpaces(): ?Spaces
    {
        return $this->spaces;
    }

    public function setSpaces(?Spaces $spaces): static
    {
        // unset the owning side of the relation if necessary
        if ($spaces === null && $this->spaces !== null) {
            $this->spaces->setContent(null);
        }

        // set the owning side of the relation if necessary
        if ($spaces !== null && $spaces->getContent() !== $this) {
            $spaces->setContent($this);
        }

        $this->spaces = $spaces;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setContent(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getContent() !== $this) {
            $user->setContent($this);
        }

        $this->user = $user;

        return $this;
    }
}
