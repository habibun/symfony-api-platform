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
    public function __construct(StatsHelper $statsHelper)
    {
        $this->statsHelper = $statsHelper;
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
        return 2;
    }

    /**
     * @return float
     */
    public function getTotalItems(): float
    {
        return 25;
    }

    /**
     * @return float
     */
    public function getCurrentPage(): float
    {
        return 1;
    }

    /**
     * @return float
     */
    public function getItemsPerPage(): float
    {
        return 10;
    }

    /**
     * @return Traversable|array
     */
    public function getIterator(): Traversable
    {
        if ($this->dailyStatsIterator === null) {
            $this->dailyStatsIterator = new \ArrayIterator(
                $this->statsHelper->fetchMany()
            );
        }
        return $this->dailyStatsIterator;
    }
}
