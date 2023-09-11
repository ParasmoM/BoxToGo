<?php

namespace App\DTO;

use App\Entity\Spaces;
use App\Entity\Addresses;
use App\Entity\Contents;
use App\Entity\Images;
use App\Entity\SpaceAmenityLinks;

class FormAddNewSpaceModel {
    private Spaces $space;
    private Addresses $adresse;
    private SpaceAmenityLinks $amenity;
    private Images $image;
    private Contents $content;

    public function __construct()
    {
        $this->space = new Spaces();
        $this->adresse = new Addresses();
        $this->amenity = new SpaceAmenityLinks();
        $this->image = new Images();
        $this->content = new Contents();
    }

    // Getters
    public function getSpace(): Spaces
    {
        return $this->space;
    }

    public function getAdresse(): Addresses
    {
        return $this->adresse;
    }

    public function getAmenity(): SpaceAmenityLinks
    {
        return $this->amenity;
    }

    public function getImage(): Images
    {
        return $this->image;
    }

    public function getContent(): Contents
    {
        return $this->content;
    }

    // Setters
    public function setSpace(Spaces $space): void
    {
        $this->space = $space;
    }

    public function setAdresse(Addresses $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setAmenity(SpaceAmenityLinks $amenity): void
    {
        $this->amenity = $amenity;
    }

    public function setImage(Images $image): void
    {
        $this->image = $image;
    }

    public function setContent(Contents $content): void
    {
        $this->content = $content;
    }
}