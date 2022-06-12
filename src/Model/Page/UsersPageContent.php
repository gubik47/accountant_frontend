<?php

namespace App\Model\Page;

use App\Model\User;

class UsersPageContent
{
    /**
     * @var User[]
     */
    private array $users = [];

    /**
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    public function setUsers(array $users): UsersPageContent
    {
        $this->users = $users;
        return $this;
    }
}