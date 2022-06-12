<?php

namespace App\Model;

class TransactionList
{
    /**
     * @var Transaction[]
     */
    private array $transactions = [];

    private Pagination $pagination;

    private int $totalCount;

    public function __construct(array $data)
    {
        foreach ($data["transactions"] as $transaction) {
            $this->transactions[] = new Transaction($transaction);
        }
        $this->pagination = new Pagination($data["pagination"]);
        $this->totalCount = intval($data["total_count"]);
    }

    /**
     * @return Transaction[]
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }
}