<?php

namespace App\DTO;

use App\Entity\User;
use App\Entity\Contents;
use App\Entity\Images;

class FormSettingsAccountModel {
    private Images $photo;
    private Contents $description;
    private User $account;

    public function __construct()
    {
        $this->photo = new Images();
        $this->description = new Contents();
        $this->account = new User();
    }

    public function getPhoto(): Images
    {
        return $this->photo;
    }
    public function setPhoto(Images $photo): void
    {
        $this->photo = $photo;
    }

    public function getDescription(): Contents
    {
        return $this->description;
    }
    public function setDescription(Contents $description): void
    {
        $this->description = $description;
    }

    public function getAccount(): User
    {
        return $this->account;
    }
    public function setAccount(User $account): void
    {
        $this->account = $account;
    }
}