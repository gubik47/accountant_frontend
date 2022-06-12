<?php

namespace App\Model\Page;

use App\Model\Account;

class AccountsPageContent
{
    /**
     * @var Account[]
     */
    private array $accounts = [];

    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function setAccounts(array $accounts): AccountsPageContent
    {
        $this->accounts = $accounts;
        return $this;
    }

    public function getTotalBalance(): float
    {
        $totalBalance = 0;
        foreach ($this->getAccounts() as $account) {
            $totalBalance += $account->getBalance();
        }

        return $totalBalance;
    }
}