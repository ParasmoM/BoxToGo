<?php

namespace App\Entity;

use App\Repository\SpaceTypesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceTypesRepository::class)]
class SpaceTypes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_fr = null;

    #[ORM\Column(length: 255)]
    private ?string $name_en = null;

    #[ORM\OneToMany(mappedBy: 'type', targetEntity: Spaces::class)]
    private Collection $space;

    public function __construct()
    {
        $this->space = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name_en;
    }

    public function getName($language)
    {
        $method = 'getName' . ucfirst(strtolower($language));

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method]);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameFr(): ?string
    {
        return $this->name_fr;
    }

    public function setNameFr(string $name_fr): static
    {
        $this->name_fr = $name_fr;

        return $this;
    }

    public function getNameEn(): ?string
    {
        return $this->name_en;
    }

    public function setNameEn(string $name_en): static
    {
        $this->name_en = $name_en;

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
            $space->setType($this);
        }

        return $this;
    }

    public function removeSpace(Spaces $space): static
    {
        if ($this->space->removeElement($space)) {
            // set the owning side to null (unless already changed)
            if ($space->getType() === $this) {
                $space->setType(null);
            }
        }

        return $this;
    }
}
