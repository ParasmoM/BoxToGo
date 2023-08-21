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
    private ?string $titleNl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionFr = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionEn = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $descriptionNl = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?Users $user = null;

    #[ORM\OneToOne(mappedBy: 'content', cascade: ['persist', 'remove'])]
    private ?Spaces $space = null;

    public function setTitle(string $language, ?string $title): self
    {
        $method = 'setTitle' . ucfirst(strtolower($language));
        if (method_exists($this, $method)) {
            $this->$method($title);
        }

        return $this;
    }

    public function setDescription(string $language, ?string $description): self
    {
        $method = 'setDescription' . ucfirst(strtolower($language));
        if (method_exists($this, $method)) {
            $this->$method($description);
        }

        return $this;
    }

    public function getTitleBasedOnLanguage($language) {
        $method = 'title' . ucfirst(strtolower($language));

        return $method;
    }

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

    public function getTitleNl(): ?string
    {
        return $this->titleNl;
    }

    public function setTitleNl(?string $titleNl): static
    {
        $this->titleNl = $titleNl;

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

    public function getDescriptionNl(): ?string
    {
        return $this->descriptionNl;
    }

    public function setDescriptionNl(?string $descriptionNl): static
    {
        $this->descriptionNl = $descriptionNl;

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
