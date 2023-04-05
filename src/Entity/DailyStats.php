<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;

#[ApiResource()]
class DailyStats
{
    public $date;

    public $totalVisitors;

    public $mostPopularListings;
}

