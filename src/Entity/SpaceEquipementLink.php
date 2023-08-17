<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\SpaceEquipementLinkRepository;

#[ORM\Entity(repositoryClass: SpaceEquipementLinkRepository::class)]
class SpaceEquipementLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    private ?Spaces $space = null;

    #[ORM\ManyToMany(inversedBy: 'equipment', targetEntity: SpaceEquipements::class)]
    private Collection $spaceEquipments;

    public function __construct()
    {
        $this->spaceEquipments = new ArrayCollection();
    }

    // public function __toString()
    // {
    //     return $this->space;
    // }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, SpaceEquipements>
     */
    public function getSpaceEquipments(): Collection
    {
        return $this->spaceEquipments;
    }

    public function addSpaceEquipment(SpaceEquipements $equipment): static
    {
        if (!$this->spaceEquipments->contains($equipment)) {
            $this->spaceEquipments[] = $equipment;
            $equipment->addEquipment($this);
        }

        return $this;
    }

    public function removeSpaceEquipment(SpaceEquipements $equipment): static
    {
        if ($this->spaceEquipments->removeElement($equipment)) {
            $equipment->removeEquipment($this);
        }

        return $this;
    }
}
