<?php

namespace App\Model\Page;

use App\Model\Account;
use App\Model\TransactionList;

class AccountDetailPageContent
{
    private ?Account $account;
    private TransactionList $transactionList;

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): AccountDetailPageContent
    {
        $this->account = $account;
        return $this;
    }

    public function getTransactionList(): TransactionList
    {
        return $this->transactionList;
    }

    public function setTransactionList(TransactionList $transactionList): AccountDetailPageContent
    {
        $this->transactionList = $transactionList;
        return $this;
    }
}