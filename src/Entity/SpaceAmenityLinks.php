<?php

namespace App\Entity;

use App\Repository\SpaceAmenityLinksRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SpaceAmenityLinksRepository::class)]
class SpaceAmenityLinks
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: SpaceAmenities::class, inversedBy: 'amenities')]
    private Collection $amenities;

    #[ORM\ManyToOne(inversedBy: 'amenties')]
    private ?Spaces $spaces = null;

    public function __construct()
    {
        $this->amenities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, SpaceAmenities>
     */
    public function getAmenities(): Collection
    {
        return $this->amenities;
    }

    public function addAmenity(SpaceAmenities $amenity): static
    {
        if (!$this->amenities->contains($amenity)) {
            $this->amenities->add($amenity);
        }

        return $this;
    }

    public function removeAmenity(SpaceAmenities $amenity): static
    {
        $this->amenities->removeElement($amenity);

        return $this;
    }

    public function getSpaces(): ?Spaces
    {
        return $this->spaces;
    }

    public function setSpaces(?Spaces $spaces): static
    {
        $this->spaces = $spaces;

        return $this;
    }
}
