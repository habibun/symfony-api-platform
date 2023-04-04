<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class UserDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $collectionDataProvider;
    public function __construct(CollectionDataProviderInterface $collectionDataProvider)
    {
        $this->collectionDataProvider = $collectionDataProvider;
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     *
     * @return iterable
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        return $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @param array $context
     *
     * @return bool
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === User::class;
    }
}
