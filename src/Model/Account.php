<?php

namespace App\Model;

class Account
{
    private int $id;

    private Bank $bank;

    private string $name;

    private string $number;

    private string $owner;

    private float $balance;

    private int $userId;

    public function __construct(array $data)
    {
        $this->id = intval($data["id"]);
        $this->bank = new Bank($data["bank"]);
        $this->name = strval($data["name"]);
        $this->number = strval($data["number"]);
        $this->owner = strval($data["owner"]);
        $this->balance = floatval($data["balance"]);
        $this->userId = intval($data["user_id"]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}