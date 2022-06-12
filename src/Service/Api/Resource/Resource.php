<?php

namespace App\Service\Api\Resource;

use App\Service\Api\ApiClient;
use App\Service\Api\RequestBlueprintFactory;
use App\Utils\Api\RequestBlueprint;
use App\Utils\Api\ResourceData;

class Resource
{
    protected ApiClient $client;

    protected RequestBlueprintFactory $factory;

    /**
     * @var RequestBlueprint[]
     */
    protected array $blueprints = [];

    public function __construct(ApiClient $client, RequestBlueprintFactory $factory)
    {
        $this->client = $client;
        $this->factory = $factory;
    }

    public function sendRequests(): ResourceData
    {
        $data = $this->client->sendRequests($this->blueprints);

        $this->blueprints = [];

        return $data;
    }
}