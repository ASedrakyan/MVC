<?php

namespace components;

class Paginator
{
    /**
     * Per page items
     * @var int $perPage
     */
    protected int $perPage;

    /**
     * Count of all items
     * @var int $count
     */
    protected int $allItemsCount;

    /**
     * Current page number
     * @var int $currentPage
     */
    protected int $currentPage;

    /**
     * Initialise parameters
     * @param $currentPage
     * @param $perPage
     * @param $allItemsCount
     */
    public function __construct($currentPage, $perPage, $allItemsCount)
    {
        $this->perPage = $perPage;
        $this->allItemsCount = $allItemsCount;
        $this->currentPage = (int) abs($currentPage) ?: 1;
    }

    public function current(): int
    {
        return $this->currentPage;
    }

    public function links(): int
    {
        return (int) ceil($this->allItemsCount / $this->perPage);
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function count(): int
    {
        return $this->allItemsCount;
    }
}