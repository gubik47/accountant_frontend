<?php

namespace App\Model;

class User
{
    private int $id;

    private string $firstName;

    private string $lastName;

    private float $totalBalance;

    public function __construct(array $data)
    {
        $this->id = intval($data["id"]);
        $this->firstName = strval($data["first_name"]);
        $this->lastName = strval($data["last_name"]);
        $this->totalBalance = floatval($data["total_balance"]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getTotalBalance(): float
    {
        return $this->totalBalance;
    }
}