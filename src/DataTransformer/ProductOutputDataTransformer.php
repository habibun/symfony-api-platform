<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\ProductOutput;
use App\Entity\Product;

class ProductOutputDataTransformer implements DataTransformerInterface
{
    public function transform($product, string $to, array $context = [])
    {
        $output = new ProductOutput();
        $output->title = $product->getTitle();

        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Product && $to === ProductOutput::class;
    }
}
