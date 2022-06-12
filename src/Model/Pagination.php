<?php

namespace App\Model;

class Pagination
{
    const NUMBER_CONTROLS_OFFSET = 2;

    private int $itemsPerPage;

    private int $currentPage;

    private int $numberOfPages;

    public function __construct(array $data)
    {
        $this->itemsPerPage = intval($data["items_per_page"]);
        $this->currentPage = intval($data["current_page"]);
        $this->numberOfPages = intval($data["number_of_pages"]);
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    public function isLoadButtonVisible(): bool
    {
        return $this->currentPage < $this->numberOfPages;
    }

    public function getMinPageToShow(): int
    {
        $minPage = $this->currentPage - self::NUMBER_CONTROLS_OFFSET;

        return max($minPage, 1);
    }

    public function getMaxPageToShow(): int
    {
        $maxPage = $this->currentPage + self::NUMBER_CONTROLS_OFFSET;

        return min($maxPage, $this->numberOfPages);
    }

    public function isPreviousPageEllipsisVisible(): bool
    {
        return $this->currentPage - self::NUMBER_CONTROLS_OFFSET > 1;
    }

    public function isNextPageEllipsisVisible(): bool
    {
        return $this->currentPage + self::NUMBER_CONTROLS_OFFSET < $this->numberOfPages;
    }
}
