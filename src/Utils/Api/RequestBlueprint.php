<?php

namespace App\Utils\Api;

use Symfony\Component\HttpFoundation\Request;

class RequestBlueprint
{
    private ?string $method = null;

    private ?string $resource = null;

    private array $data = [];

    private ?string $containerName = null;

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(?string $method): RequestBlueprint
    {
        $this->method = $method;
        return $this;
    }

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(?string $resource): RequestBlueprint
    {
        $this->resource = $resource;
        return $this;
    }

    public function getContainerName(): ?string
    {
        return $this->containerName;
    }

    public function setContainerName(?string $containerName): RequestBlueprint
    {
        $this->containerName = $containerName;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): RequestBlueprint
    {
        $this->data = $data;
        return $this;
    }

    public function getUri(): string
    {
        if ($this->method !== Request::METHOD_GET) {
            return $this->resource;
        }

        return $this->resource . "?" . http_build_query($this->data);
    }

    public function getBodyData(): array
    {
        if ($this->method === Request::METHOD_POST || $this->method === Request::METHOD_PUT) {
            return $this->data;
        }

        return [];
    }

    public function setDataItem(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
}
