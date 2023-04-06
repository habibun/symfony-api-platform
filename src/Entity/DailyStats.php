<?php

namespace App\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
    ],
    normalizationContext: ['groups' => ['daily-stats:read'], 'swagger_definition_name' => 'Read'],
    paginationItemsPerPage: 7
)]
class DailyStats
{
    #[Groups(['daily-stats:read'])]
    public $date;

    #[Groups(['daily-stats:read'])]
    public $totalVisitors;

    /**
     * The 5 most popular cheese listings from this date!
     * 
     * @var array<Product>|Product[]
     */
    #[Groups(['daily-stats:read'])]
    public $mostPopularListings;

    /**
     * @param array|Product[] $mostPopularListings
     */
    public function __construct(\DateTimeInterface $date, int $totalVisitors, array $mostPopularListings)
    {
        $this->date = $date;
        $this->totalVisitors = $totalVisitors;
        $this->mostPopularListings = $mostPopularListings;
    }

    #[ApiProperty(identifier: true)]
    public function getDateString(): string
    {
        return $this->date->format('Y-m-d');
    }
}

