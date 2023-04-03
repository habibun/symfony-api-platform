<?php

namespace App\Factory;

use App\Entity\ProductNotification;
use App\Repository\ProductNotificationRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static ProductNotification|Proxy findOrCreate(array $attributes)
 * @method static ProductNotification|Proxy random()
 * @method static ProductNotification[]|Proxy[] randomSet(int $number)
 * @method static ProductNotification[]|Proxy[] randomRange(int $min, int $max)
 * @method static ProductNotificationRepository|RepositoryProxy repository()
 * @method ProductNotification|Proxy create($attributes = [])
 * @method ProductNotification[]|Proxy[] createMany(int $number, $attributes = [])
 */
final class ProductNotificationFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'cheeseListing' => ProductFactory::new(),
            'notificationText' => self::faker()->realText(50),
        ];
    }

    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Product $product): void {})
            ;
    }

    protected static function getClass(): string
    {
        return ProductNotification::class;
    }
}
