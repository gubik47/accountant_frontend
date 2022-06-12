<?php

namespace App\Service\Api\Resource;

use App\Utils\Api\ResourceData;

class TransactionResource extends Resource
{
    public function uploadTransactions(array $data): ResourceData
    {
        $this->blueprints[] = $this->factory->createTransactionsUploadRequestBlueprint($data);

        return $this->sendRequests();
    }
}