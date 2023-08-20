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
    private ?string $titleFr = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleEn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $titleNe = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionFr = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionNe = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?Users $user = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?Spaces $space = null;

    public function getTitleFr(): ?string
    {
        return $this->titleFr;
    }

    public function setTitleFr(?string $titleFr): static
    {
        $this->titleFr = $titleFr;

        return $this;
    }

    public function getTitleEn(): ?string
    {
        return $this->titleEn;
    }

    public function setTitleEn(?string $titleEn): static
    {
        $this->titleEn = $titleEn;

        return $this;
    }

    public function getTitleNe(): ?string
    {
        return $this->titleNe;
    }

    public function setTitleNe(?string $titleNe): static
    {
        $this->titleNe = $titleNe;

        return $this;
    }

    public function getDescriptionFr(): ?string
    {
        return $this->descriptionFr;
    }

    public function setDescriptionFr(?string $descriptionFr): static
    {
        $this->descriptionFr = $descriptionFr;

        return $this;
    }

    public function getDescriptionEn(): ?string
    {
        return $this->descriptionEn;
    }

    public function setDescriptionEn(?string $descriptionEn): static
    {
        $this->descriptionEn = $descriptionEn;

        return $this;
    }

    public function getDescriptionNe(): ?string
    {
        return $this->descriptionNe;
    }

    public function setDescriptionNe(?string $descriptionNe): static
    {
        $this->descriptionNe = $descriptionNe;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): static
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

    public function getSpace(): ?Spaces
    {
        return $this->space;
    }

    public function setSpace(?Spaces $space): static
    {
        // unset the owning side of the relation if necessary
        if ($space === null && $this->space !== null) {
            $this->space->setContent(null);
        }

        // set the owning side of the relation if necessary
        if ($space !== null && $space->getContent() !== $this) {
            $space->setContent($this);
        }

        $this->space = $space;

        return $this;
    }

    
}
