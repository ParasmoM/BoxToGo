<?php

namespace App\ClassManager;

class NavbarManager
{
    private $items = array();

    public function push(NavbarItem $item) 
    {
        $this->items[] = $item;
    }

    public function getItems(): ?array
    {
        return $this->items;
    }
}
