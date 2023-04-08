<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductOutput;
use App\Entity\Product;

class ProductOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param Product $product
     * @param string $to
     * @param array $context
     *
     * @return ProductOutput
     */
    public function transform($product, string $to, array $context = [])
    {
        return ProductOutput::createFromEntity($product);

    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Product && $to === ProductOutput::class;
    }
}
