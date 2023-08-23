<?php

namespace App\DTO;

use App\Entity\Spaces;
use App\Entity\Adresses;
use App\Entity\Contents;
use App\Entity\SpaceImages;
use App\Entity\SpaceEquipementLink;

class FormAddNewSpaceModel {
    private Spaces $space;
    private Adresses $adresse;
    private SpaceEquipementLink $equipment;
    private SpaceImages $image;
    private Contents $content;

    public function __construct()
    {
        $this->space = new Spaces();
        $this->adresse = new Adresses();
        $this->equipment = new SpaceEquipementLink();
        $this->image = new SpaceImages();
        $this->content = new Contents();
    }

    // Getters
    public function getSpace(): Spaces
    {
        return $this->space;
    }

    public function getAdresse(): Adresses
    {
        return $this->adresse;
    }

    public function getEquipment(): SpaceEquipementLink
    {
        return $this->equipment;
    }

    public function getImage(): SpaceImages
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

    public function setAdresse(Adresses $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setEquipment(SpaceEquipementLink $equipment): void
    {
        $this->equipment = $equipment;
    }

    public function setImage(SpaceImages $image): void
    {
        $this->image = $image;
    }

    public function setContent(Contents $content): void
    {
        $this->content = $content;
    }
}