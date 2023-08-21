<?php

namespace App\DTO;

use App\Entity\Spaces;
use App\Entity\Adresses;
use App\Entity\Contents;
use App\Entity\SpaceEquipementLink;

class DynamicCompositeModel {
    private Contents $content;
    private Spaces $space;
    private Adresses $adresse;
    private SpaceEquipementLink $equipment;

    public function __construct()
    {
        $this->content = new Contents();
        $this->space = new Spaces();
        $this->adresse = new Adresses();
        $this->equipment = new SpaceEquipementLink();
    }

    public function hydrate(Spaces $space): void
    {
        $this->setSpace($space);
        $this->setAdresse($space->getAdresse()->first()); 
        $this->setContent($space->getContent());
        $this->setEquipment($space->getEquipment()->first());
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

    public function getAdresse(): Adresses
    {
        return $this->adresse;
    }

    public function setAdresse(Adresses $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function getEquipment(): SpaceEquipementLink
    {
        return $this->equipment;
    }

    public function setEquipment(SpaceEquipementLink $equipment): void
    {
        $this->equipment = $equipment;
    }
}