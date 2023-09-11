<?php

namespace App\Traits;

trait IdTrait {
    public function getId(): ?int
    {
        return $this->id;
    }
}