<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\ParameterBag;

class TransactionRequestQueryParser
{
    public function parseQuery(ParameterBag $query): array
    {
        $parsedQuery = [];

        $limit = $query->get("limit");
        if ($limit !== null) {
            $parsedQuery["limit"] = intval($limit);
        }

        $pageNumber = $query->get("page");
        if ($pageNumber && $this->isPageNumberValid($pageNumber)) {
            $parsedQuery["page"] = intval($pageNumber);
        }

        if ($account = $query->get("account")) {
            $parsedQuery["account"] = intval($account);
        }

        return $parsedQuery;
    }

    private function isPageNumberValid(mixed $pageNumber): bool
    {
        if (!is_numeric($pageNumber)) {
            return false;
        }

        $pageNumber = intval($pageNumber);
        if ($pageNumber < 1) {
            return false;
        }

        return true;
    }
}
