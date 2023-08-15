<?php

namespace App\Entity;

use App\Repository\SpaceCategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceCategoriesRepository::class)]
class SpaceCategories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'spaceCateg', targetEntity: Spaces::class)]
    private Collection $space;

    public function __construct()
    {
        $this->space = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Spaces>
     */
    public function getSpace(): Collection
    {
        return $this->space;
    }

    public function addSpace(Spaces $space): static
    {
        if (!$this->space->contains($space)) {
            $this->space->add($space);
            $space->setSpaceCateg($this);
        }

        return $this;
    }

    public function removeSpace(Spaces $space): static
    {
        if ($this->space->removeElement($space)) {
            // set the owning side to null (unless already changed)
            if ($space->getSpaceCateg() === $this) {
                $space->setSpaceCateg(null);
            }
        }

        return $this;
    }
}
