<?php

namespace App\Service\Api;

use App\Utils\Api\RequestBlueprint;
use App\Utils\Api\RequestOptions;
use Symfony\Component\HttpFoundation\Request;

class RequestBlueprintFactory
{
    protected function create(array $options): RequestBlueprint
    {
        $blueprint = (new RequestBlueprint())
            ->setMethod($options["method"])
            ->setContainerName($options["container_name"])
            ->setResource($options["resource"]);

        if (isset($options["data"])) {
            $blueprint->setData($options["data"]);
        }

        return $blueprint;
    }

    public function createUsersRequestBlueprint(): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_GET,
            "resource" => RequestOptions::RESOURCE_USERS,
            "container_name" => "users"
        ]);
    }

    public function createUserRequestBlueprint(int $id): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_GET,
            "resource" => str_replace("{id}", $id, RequestOptions::RESOURCE_USER),
            "container_name" => "user"
        ]);
    }

    public function createAccountsRequestBlueprint(int $userId): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_GET,
            "resource" => RequestOptions::RESOURCE_ACCOUNTS,
            "container_name" => "accounts",
            "data" => [
                "user" => $userId
            ]
        ]);
    }

    public function createAccountRequestBlueprint(int $id): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_GET,
            "resource" => str_replace("{id}", $id, RequestOptions::RESOURCE_ACCOUNT),
            "container_name" => "account"
        ]);
    }

    public function createTransactionsRequestBlueprint(array $options): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_GET,
            "resource" => RequestOptions::RESOURCE_TRANSACTIONS,
            "container_name" => "transactions",
            "data" => $options
        ]);
    }

    public function createTransactionsUploadRequestBlueprint(array $data): RequestBlueprint
    {
        return $this->create([
            "method" => Request::METHOD_POST,
            "resource" => RequestOptions::RESOURCE_ADD_TRANSACTIONS,
            "container_name" => "upload",
            "data" => $data
        ]);
    }
}
