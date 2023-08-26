<?php

namespace App\DTO;

use App\Entity\Users;
use App\Entity\Contents;
use App\Entity\SpaceImages;

class FormSettingsAccountModel {
    private SpaceImages $photo;
    private Contents $description;
    private Users $account;

    public function __construct()
    {
        $this->photo = new SpaceImages();
        $this->description = new Contents();
        $this->account = new Users();
    }

    public function getPhoto(): SpaceImages
    {
        return $this->photo;
    }
    public function setPhoto(SpaceImages $photo): void
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

    public function getAccount(): Users
    {
        return $this->account;
    }
    public function setAccount(Users $account): void
    {
        $this->account = $account;
    }
}