<?php

namespace App\Service\Api\Resource;

use App\Utils\Api\ResourceData;

class UserResource extends Resource
{
    public function getUsers(): ResourceData
    {
        $this->blueprints[] = $this->factory->createUsersRequestBlueprint();

        return $this->sendRequests();
    }

    public function getUser(int $userId): ResourceData
    {
        $this->blueprints[] = $this->factory->createUserRequestBlueprint($userId);

        return $this->sendRequests();
    }
}