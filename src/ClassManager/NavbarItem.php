<?php

namespace App\ClassManager;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NavbarItem
{
    private $path;
    private $name;
    private $isActive;
    private $content;

    public function __construct($name, $path, $isActive, $content = null) 
    {
        $this->name = $name;
        $this->path = $path;
        $this->isActive = $isActive;
        $this->content = $content;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getIsActive(): ?string
    {
        return $this->isActive;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }
}