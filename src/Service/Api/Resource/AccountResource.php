<?php

namespace App\Service\Api\Resource;

use App\Utils\Api\ResourceData;

class AccountResource extends Resource
{
    public function getAccounts(int $userId): ResourceData
    {
        $this->blueprints[] = $this->factory->createAccountsRequestBlueprint($userId);

        return $this->sendRequests();
    }

    public function getAccountDetailPageData(int $accountId): ResourceData
    {
        $this->blueprints[] = $this->factory->createAccountRequestBlueprint($accountId);

        $transactionOptions = [
            "account" => $accountId
        ];

        $this->blueprints[] = $this->factory->createTransactionsRequestBlueprint($transactionOptions);

        return $this->sendRequests();
    }
}