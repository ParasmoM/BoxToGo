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
    private ?string $name_fr = null;

    #[ORM\Column(length: 255)]
    private ?string $name_en = null;

    #[ORM\ManyToMany(targetEntity: SpaceAmenityLinks::class, mappedBy: 'amenities')]
    private Collection $amenityLinks;

    public function __construct()
    {
        $this->amenityLinks = new ArrayCollection();
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
