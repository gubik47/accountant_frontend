<?php

namespace App\Service\Factory;

use App\Model\Account;
use App\Model\Page\AccountDetailPageContent;
use App\Model\Page\AccountsPageContent;
use App\Model\Page\UsersPageContent;
use App\Model\TransactionList;
use App\Model\User;
use App\Service\Api\Resource\AccountResource;
use App\Service\Api\Resource\UserResource;
use App\Service\TransactionRequestQueryParser;
use Symfony\Component\HttpFoundation\Request;

class PageFactory
{
    private UserResource $userResource;
    private AccountResource $accountResource;
    private TransactionRequestQueryParser $transactionRequestQueryParser;

    public function __construct(UserResource $userResource, AccountResource $accountResource, TransactionRequestQueryParser $transactionRequestQueryParser)
    {
        $this->userResource = $userResource;
        $this->accountResource = $accountResource;
        $this->transactionRequestQueryParser = $transactionRequestQueryParser;
    }

    public function createUsersPageContent(): UsersPageContent
    {
        $data = $this->userResource->getUsers();

        $content = new UsersPageContent();

        $users = [];
        foreach ($data->users as $item) {
            $users[] = new User($item);
        }

        $content->setUsers($users);

        return $content;
    }

    public function createAccountsPageContent(int $userId): AccountsPageContent
    {
        $data = $this->accountResource->getAccounts($userId);

        $content = new AccountsPageContent();

        $accounts = [];
        foreach ($data->accounts as $item) {
            $accounts[] = new Account($item);
        }

        $content->setAccounts($accounts);

        return $content;
    }

    public function createAccountDetailPageContent(int $accountId, Request $request): AccountDetailPageContent
    {
        $transactionsOptions = $this->transactionRequestQueryParser->parseQuery($request->query);
        $transactionsOptions["account"] = $accountId;

        $data = $this->accountResource->getAccountDetailPageData($accountId, $transactionsOptions);

        $content = new AccountDetailPageContent();

        $content->setAccount(new Account($data->account))
            ->setTransactionList(new TransactionList($data->transactions));

        return $content;
    }
}