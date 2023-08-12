<?php

namespace App\Entity;

use App\Repository\SpaceEquipementLinkRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceEquipementLinkRepository::class)]
class SpaceEquipementLink
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantity = null;

    #[ORM\Column]
    private ?bool $equipped = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    private ?Spaces $space = null;

    #[ORM\ManyToOne(inversedBy: 'equipment')]
    private ?SpaceEquipements $equipmentName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function isEquipped(): ?bool
    {
        return $this->equipped;
    }

    public function setEquipped(bool $equipped): static
    {
        $this->equipped = $equipped;

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

    public function getEquipmentName(): ?SpaceEquipements
    {
        return $this->equipmentName;
    }

    public function setEquipmentName(?SpaceEquipements $equipmentName): static
    {
        $this->equipmentName = $equipmentName;

        return $this;
    }
}
