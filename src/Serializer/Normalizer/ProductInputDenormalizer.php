<?php

namespace App\Serializer\Normalizer;

use App\Dto\ProductInput;
use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class ProductInputDenormalizer implements DenormalizerInterface, CacheableSupportsMethodInterface
{
    private $objectNormalizer;
    public function __construct(ObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $this->createDto($context);
        return $this->objectNormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization($data, string $type, string $format = null)
    {
        return $type === ProductInput::class;
    }

    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    private function createDto(array $context): ProductInput
    {
        $entity = $context['object_to_populate'] ?? null;
        if ($entity && !$entity instanceof Product) {
            throw new \Exception(sprintf('Unexpected resource class "%s"', get_class($entity)));
        }

        return ProductInput::createFromEntity($entity);
    }
}