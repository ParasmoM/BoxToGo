<?php

namespace App\DTO;

use App\Entity\Images;
use App\Entity\Spaces;
use App\Entity\Contents;
use App\Entity\Addresses;
use App\Entity\SpaceAmenityLinks;

class FormEditSpaceModel {
    private Contents $content;
    private Spaces $space;
    private Addresses $adresse;
    private SpaceAmenityLinks $amenity;
    private Images $galleries;

    public function __construct()
    {
        $this->content = new Contents();
        $this->space = new Spaces();
        $this->adresse = new Addresses();
        $this->amenity = new SpaceAmenityLinks();
        $this->galleries = new Images();
    }

    public function hydrate(Spaces $space): void
    {
        $this->setSpace($space);
        // dd($this->space->getAdresse(), $space->getAmenities()->first()->getAmenities());
        if ($space->getAdresse()) {
            $this->setAdresse($space->getAdresse()); 
        }
        $this->setContent($space->getContent());
        if ($space->getAmenities()) {
            $this->setAmenity($space->getAmenities()->first());
        }
        // dd($this->amenity);

        // $this->setGalleries($space->getImage());
    }    

    public function getContent(): Contents
    {
        return $this->content;
    }

    public function setContent(Contents $content): void
    {
        $this->content = $content;
    }

    public function getSpace(): Spaces
    {
        return $this->space;
    }

    public function setSpace(Spaces $space): void
    {
        $this->space = $space;
    }

    public function getAdresse(): Addresses
    {
        return $this->adresse;
    }

    public function setAdresse(Addresses $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getAmenity(): SpaceAmenityLinks
    {
        return $this->amenity;
    }

    public function setAmenity(SpaceAmenityLinks $amenity): void
    {
        $this->amenity = $amenity;
    }

    public function getGalleries(): Images
    {
        return $this->galleries;
    }

    public function setGalleries(Images $galleries): void
    {
        $this->galleries = $galleries;
    }
}