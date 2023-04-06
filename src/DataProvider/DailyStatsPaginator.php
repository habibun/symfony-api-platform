<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Exception\ResourceClassNotSupportedException;
use ApiPlatform\State\Pagination\PaginatorInterface;
use App\Service\StatsHelper;
use Exception;
use Traversable;

class DailyStatsPaginator implements PaginatorInterface, \IteratorAggregate
{
    private $statsHelper;
    private int $currentPage;
    private int $maxResults;

    public function __construct(StatsHelper $statsHelper, int $currentPage, int $maxResults)
    {
        $this->statsHelper = $statsHelper;
        $this->currentPage = $currentPage;
        $this->maxResults = $maxResults;
    }

    private $dailyStatsIterator;

    /**
     * @return int
     */
    public function count(): int
    {
        return iterator_count($this->getIterator());
    }

    /**
     * @return float
     */
    public function getLastPage(): float
    {
        return ceil($this->getTotalItems() / $this->getItemsPerPage()) ?: 1.;
    }

    /**
     * @return float
     */
    public function getTotalItems(): float
    {
        return $this->statsHelper->count();
    }

    /**
     * @return float
     */
    public function getCurrentPage(): float
    {
        return $this->currentPage;
    }

    /**
     * @return float
     */
    public function getItemsPerPage(): float
    {
        return $this->maxResults;
    }

    /**
     * @return Traversable|array
     */
    public function getIterator(): Traversable
    {
        if ($this->dailyStatsIterator === null) {
            $offset = (($this->getCurrentPage() - 1) * $this->getItemsPerPage());
            $this->dailyStatsIterator = new \ArrayIterator(
                $this->statsHelper->fetchMany(
                    $this->getItemsPerPage(),
                    $offset
                )
            );
        }
        return $this->dailyStatsIterator;
    }
}
