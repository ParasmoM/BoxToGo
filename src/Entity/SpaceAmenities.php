<?php

namespace App\Entity;

use App\Repository\SpaceAmenitiesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceAmenitiesRepository::class)]
class SpaceAmenities
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: SpaceAmenityLinks::class, mappedBy: 'amenities')]
    private Collection $amenities;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
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
     * @return Collection<int, SpaceAmenityLinks>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(SpaceAmenityLinks $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
            $amenity->addAmenity($this);
        }

        return $this;
    }

    public function removeAmenity(SpaceAmenityLinks $amenity): static
    {
        if ($this->amenities->removeElement($amenity)) {
            $amenity->removeAmenity($this);
        }

        return $this;
    }
}
