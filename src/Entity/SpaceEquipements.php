<?php

namespace App\Entity;

use App\Repository\SpaceEquipementsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceEquipementsRepository::class)]
class SpaceEquipements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\ManyToMany(mappedBy: 'spaceEquipments', targetEntity: SpaceEquipementLink::class, cascade: ["persist"])]
    private Collection $equipment;

    public function __construct()
    {
        // $this->quantity = 0;
        // $this->equipped = true;
        $this->equipment = new ArrayCollection();
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

    // public function getQuantity(): ?int
    // {
    //     return $this->quantity;
    // }

    // public function setQuantity(?int $quantity): static
    // {
    //     $this->quantity = $quantity;

    //     return $this;
    // }

    // public function isEquipped(): ?bool
    // {
    //     return $this->equipped;
    // }

    // public function setEquipped(bool $equipped): static
    // {
    //     $this->equipped = $equipped;

    //     return $this;
    // }

    /**
     * @return Collection<int, SpaceEquipementLink>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(SpaceEquipementLink $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment[] = $equipment;
            $equipment->addSpaceEquipment($this); 
        }

        return $this;
    }

    public function removeEquipment(SpaceEquipementLink $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            $equipment->removeSpaceEquipment($this); 
        }

        return $this;
    }
}
