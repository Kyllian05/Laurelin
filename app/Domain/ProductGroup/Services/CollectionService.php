<?php

namespace App\Domain\ProductGroup\Services;

use App\Domain\ProductGroup\Entities\CollectionEntity;
use App\Domain\ProductGroup\Repositories\CollectionRepository;

class CollectionService
{
    public function __construct(private CollectionRepository $collectionRepository) {}

    public function getAll(): array
    {
        return $this->collectionRepository->getAll();
    }

    public function getAllSerialize(): array
    {
        $collections = $this->collectionRepository->getAll();
        $serializedCollections = [];
        foreach ($collections as $collection) {
            $serializedCollections[] = $collection->serialize();
        }
        return $serializedCollections;
    }

    public function findByName(string $name): ?CollectionEntity
    {
        return $this->collectionRepository->findByName($name);
    }

    public function getProducts(CollectionEntity $collectionEntity): array
    {
        $this->collectionRepository->getProducts($collectionEntity);
        return $collectionEntity->getProductList();
    }
}
