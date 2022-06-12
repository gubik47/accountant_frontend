<?php

namespace App\Model;

class Bank
{
    private int $id;

    private string $name;

    private string $code;

    public function __construct(array $data)
    {
        $this->id = intval($data["id"]);
        $this->name = strval($data["name"]);
        $this->code = strval($data["code"]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}