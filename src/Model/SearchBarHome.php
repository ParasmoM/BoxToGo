<?php

namespace App\Model;

use DateTime;

class SearchBarHome {
    private ?string $search = null;
    private ?DateTime $dateStart;
    private ?DateTime $dateEnd;

    public function getSearch(): ?string
    {
        return $this->search;
    }

    public function setSearch(string $search): self
    {
        $this->search = $search;
        return $this;
    }

    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    public function setDateStart(?DateTime $dateStart): self
    {
        $this->dateStart = $dateStart;
        return $this;
    }

    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    public function setDateEnd(?DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;
        return $this;
    }
}
