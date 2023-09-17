<?php

namespace App\Model;

class SearchDataAdmin
{
    public string $reference;
    public string $status;
    public string $category;
    public string $customer;

    public function getReference()
    {
        return $this->reference;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCategory()
    {
        return $this->category;
    }
    
    public function getCustomer()
    {
        return $this->customer;
    }
}