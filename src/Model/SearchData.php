<?php

namespace App\Model;

class SearchData
{
    public string $search = '';

    public function getSearch()
    {
        return $this->search;
    }
}