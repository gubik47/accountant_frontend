<?php

namespace App\Utils\Api;

use RuntimeException;

class ResourceData
{
    protected array $values = [];

    public function __get($key)
    {
        if (!array_key_exists($key, $this->values)) {
            throw new RuntimeException("Key $key does not exist");
        }

        return $this->values[$key];
    }

    public function __set($key, $value): void
    {
        $this->values[$key] = $value;
    }

    public function has($key): bool
    {
        if (array_key_exists($key, $this->values)) {
            return true;
        }
        return false;
    }
}
