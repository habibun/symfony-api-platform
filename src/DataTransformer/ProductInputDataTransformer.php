<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use App\Dto\ProductInput;
use App\Entity\Product;

class ProductInputDataTransformer implements DataTransformerInterface
{
    /**
     * @param ProductInput $input
     * @param string $to
     * @param array $context
     *
     * @return Product
     */
    public function transform($input, string $to, array $context = [])
    {
        if (isset($context[AbstractItemNormalizer::OBJECT_TO_POPULATE])) {
            $cheeseListing = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        } else {
            $cheeseListing = new Product($input->name);
        }

        $cheeseListing->setDescription($input->description);
        $cheeseListing->setPrice($input->price);
        $cheeseListing->setManufacturer($input->manufacturer);
        $cheeseListing->setIsActive($input->isActive);
        return $cheeseListing;
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
