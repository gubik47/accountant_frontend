<?php

namespace App\Utils\Api;

final class RequestOptions
{
    // user
    const RESOURCE_USERS = "users";
    const RESOURCE_USER = "users/{id}";

    // account
    const RESOURCE_ACCOUNTS = "accounts";
    const RESOURCE_ACCOUNT = "accounts/{id}";

    // transactions
    const RESOURCE_TRANSACTIONS = "transactions";
    const RESOURCE_ADD_TRANSACTIONS = "transactions/add";
}
