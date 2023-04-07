<?php

namespace App\Dto;

use App\Entity\User;
use Carbon\Carbon;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;

class ProductOutput
{
    /**
     * The title of this listing
     */
    #[Groups(['product:read'])]
    public $name;

    #[Groups(['product:read'])]
    public float $price = 0;

    #[Groups(['product:read'])]
    public ?string $description = null;

    #[Groups(['product:read'])]
    public ?User $manufacturer = null;

    public $createdAt;

    #[Groups(['product:read'])]
    public function getShortDescription(): ?string
    {
        if (strlen($this->description) < 40) {
            return $this->description;
        }
        return substr($this->description, 0, 40).'...';
    }

    /**
     * How long ago in text that this cheese listing was added.
     */
    #[Groups(['product:read'])]
    public function getCreatedAtAgo(): string
    {
        return Carbon::instance($this->getCreatedAt())->diffForHumans();
    }
}
