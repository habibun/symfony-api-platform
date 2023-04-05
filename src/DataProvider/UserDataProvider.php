<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\DenormalizedIdentifiersAwareItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class UserDataProvider implements ContextAwareCollectionDataProviderInterface, DenormalizedIdentifiersAwareItemDataProviderInterface, RestrictedDataProviderInterface
{
    private $collectionDataProvider;
    private ItemDataProviderInterface $itemDataProvider;
    private Security $security;

    public function __construct(CollectionDataProviderInterface $collectionDataProvider, ItemDataProviderInterface $itemDataProvider, Security $security)
    {
        $this->collectionDataProvider = $collectionDataProvider;
        $this->itemDataProvider = $itemDataProvider;
        $this->security = $security;
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
        /** @var User[] $users */
        $users = $this->collectionDataProvider->getCollection($resourceClass, $operationName, $context);
        $currentUser = $this->security->getUser();
        foreach ($users as $user) {
            // now handled in a listener
//            $user->setIsMe($currentUser === $user);
        }
        return $users;
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

    /**
     * @param string $resourceClass
     * @param $id
     * @param string|null $operationName
     * @param array $context
     *
     * @return object|null
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        /** @var User|null $item */
        $item = $this->itemDataProvider->getItem($resourceClass, $id, $operationName, $context);
        if (!$item) {
            return null;
        }
        // now handled in a listener
//        $item->setIsMe($this->security->getUser() === $item);
        return $item;
    }
}
