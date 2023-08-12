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

    #[ORM\OneToMany(mappedBy: 'equipmentName', targetEntity: SpaceEquipementLink::class)]
    private Collection $equipment;

    public function __construct()
    {
        $this->equipment = new ArrayCollection();
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
     * @return Collection<int, SpaceEquipementLink>
     */
    public function getEquipment(): Collection
    {
        return $this->equipment;
    }

    public function addEquipment(SpaceEquipementLink $equipment): static
    {
        if (!$this->equipment->contains($equipment)) {
            $this->equipment->add($equipment);
            $equipment->setEquipmentName($this);
        }

        return $this;
    }

    public function removeEquipment(SpaceEquipementLink $equipment): static
    {
        if ($this->equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getEquipmentName() === $this) {
                $equipment->setEquipmentName(null);
            }
        }

        return $this;
    }
}
