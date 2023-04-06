<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ApiResource(
    operations: [
        new Get(controller: NotFoundAction::class, output: false, read: false),
        new GetCollection(),
    ],
)]
class DailyStats
{
    public $date;

    public $totalVisitors;

    public $mostPopularListings;

    #[ApiProperty(identifier: true)]

    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}

