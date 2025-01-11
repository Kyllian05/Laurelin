<?php

namespace App\Domain\ProductGroup\Services;

use App\Domain\ProductGroup\Entities\CollectionEntity;
use App\Domain\ProductGroup\Repositories\CollectionRepository;
use App\Domain\Produit\Entities\ProduitEntity;

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

    public function findById(int $id): ?CollectionEntity
    {
        return $this->collectionRepository->findById($id);
    }

    public function getProducts(CollectionEntity $collectionEntity): array
    {
        $this->collectionRepository->getProducts($collectionEntity);
        return $collectionEntity->getProductList();
    }

    public function findByProduct(ProduitEntity $product): ?CollectionEntity
    {
        return $this->collectionRepository->findByProduct($product);
    }
}
