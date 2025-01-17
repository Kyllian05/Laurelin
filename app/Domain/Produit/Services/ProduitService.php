<?php

namespace App\Domain\Produit\Services;

use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ImageRepository;
use App\Domain\Produit\Repositories\ProduitRepository;

class ProduitService
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private ImageRepository $imageRepository,
    ) {}

    public function findById(int $id): ?ProduitEntity
    {
        return $this->produitRepository->findById($id);
    }

    public function findAll(array $ids) : array{
        return $this->produitRepository->findAll($ids);
    }

    public function getImages(ProduitEntity $produit): array
    {
        $this->imageRepository->getAllProductImages($produit);
        return $produit->getImages();
    }

    public function serialize(ProduitEntity $produit): array
    {
        $this->imageRepository->getAllProductImages($produit);
        return $produit->serialize();
    }

    public function serializes(array $produits): array{
        $this->imageRepository->getAllProductsImages($produits);
        $result = [];
        foreach ($produits as $produit){
            assert($produit instanceof ProduitEntity);
            $result[] = $produit->serialize();
        }
        return $result;
    }

    public function serializeWithoutImages(ProduitEntity $produit): array{
        return $produit->serialize();

    }
}
