<?php

namespace App\Entity;

use App\Repository\ProductNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductNotificationRepository::class)]
class ProductNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Product::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $product;

    #[ORM\Column(type: 'string')]
    private $notificationText;

    public function __construct(Product $product, string $notificationText)
    {
        $this->product = $product;
        $this->notificationText = $notificationText;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): ProductNotification
    {
        $this->product = $product;

        return $this;
    }

    public function getNotificationText(): string
    {
        return $this->notificationText;
    }

    public function setNotificationText(string $notificationText): ProductNotification
    {
        $this->notificationText = $notificationText;

        return $this;
    }
}
