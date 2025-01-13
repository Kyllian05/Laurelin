<?php

namespace App\Domain\ProductGroup\Services;

use App\Domain\ProductGroup\Entities\CategorieEntity;
use App\Domain\ProductGroup\Repositories\CategorieRepository;
use App\Domain\Produit\Entities\ProduitEntity;

class CategorieService
{
    public function __construct(private CategorieRepository $categorieRepository) {}

    public function getAll(): array
    {
        return $this->categorieRepository->getAll();
    }

    public function getAllSerialize(): array
    {
        $categories = $this->categorieRepository->getAll();
        $serializedCategories = [];
        foreach ($categories as $category) {
            $serializedCategories[] = $category->serialize();
        }
        return $serializedCategories;
    }

    public function findByName(string $name): ?CategorieEntity
    {
        return $this->categorieRepository->findByName($name);
    }

    public function findById(int $id): ?CategorieEntity
    {
        return $this->categorieRepository->findById($id);
    }

    public function getProducts(CategorieEntity $category): array
    {
        $this->categorieRepository->getProducts($category);
        return $category->getProductList();
    }

    public function findByProduct(ProduitEntity $product): ?CategorieEntity
    {
        return $this->categorieRepository->findByProduct($product);
    }
}
