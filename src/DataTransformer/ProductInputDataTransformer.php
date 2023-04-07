<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductInput;
use App\Entity\Product;

class ProductInputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
    }
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Product) {
            // already transformed
            return false;
        }
        return $to === Product::class && ($context['input']['class'] ?? null) === ProductInput::class;
    }
}
