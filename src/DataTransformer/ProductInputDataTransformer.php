<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Validator\ValidatorInterface;
use App\Dto\ProductInput;
use App\Entity\Product;

class ProductInputDataTransformer implements DataTransformerInterface
{
    private $validator;
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param ProductInput $input
     * @param string $to
     * @param array $context
     *
     * @return Product
     */
    public function transform($input, string $to, array $context = [])
    {
        $this->validator->validate($input);

        $product = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;

        return $input->createOrUpdateEntity($product);
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
