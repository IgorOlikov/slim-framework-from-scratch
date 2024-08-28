<?php

namespace App\Http\Services;

use App\Http\Services\Interfaces\ServiceInterface;

class TestService implements ServiceInterface
{

    public string $firstName;

    public string $surName;


    public function __construct(string $firstName, string $surName)
    {
        $this->firstName = $firstName;
        $this->surName = $surName;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->surName;
    }



}