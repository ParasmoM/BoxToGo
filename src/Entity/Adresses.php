<?php

namespace App\Entity;

use App\Repository\AdressesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressesRepository::class)]
class Adresses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $Country = null;

    #[ORM\Column(length: 255)]
    private ?string $City = null;

    #[ORM\Column(length: 20)]
    private ?string $StreetNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $Street = null;

    #[ORM\Column(length: 20)]
    private ?string $PostalCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): static
    {
        $this->Country = $Country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): static
    {
        $this->City = $City;

        return $this;
    }

    public function getStreetNumber(): ?string
    {
        return $this->StreetNumber;
    }

    public function setStreetNumber(string $StreetNumber): static
    {
        $this->StreetNumber = $StreetNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->Street;
    }

    public function setStreet(string $Street): static
    {
        $this->Street = $Street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->PostalCode;
    }

    public function setPostalCode(string $PostalCode): static
    {
        $this->PostalCode = $PostalCode;

        return $this;
    }
}
