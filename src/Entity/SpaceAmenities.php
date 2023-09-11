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
    private Collection $amenityLinks;

    public function __construct()
    {
        $this->amenityLinks = new ArrayCollection();
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
    public function getAmenityLinks(): Collection
    {
        return $this->amenityLinks;
    }

    public function addAmenityLink(SpaceAmenityLinks $amenityLink): static
    {
        if (!$this->amenityLinks->contains($amenityLink)) {
            $this->amenityLinks->add($amenityLink);
            $amenityLink->addAmenity($this);
        }

        return $this;
    }

    public function removeAmenityLink(SpaceAmenityLinks $amenityLink): static
    {
        if ($this->amenityLinks->removeElement($amenityLink)) {
            $amenityLink->removeAmenity($this);
        }

        return $this;
    }
}
