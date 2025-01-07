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

    public function getImages(ProduitEntity $produit): array
    {
        $this->imageRepository->getAllProductImages($produit);
        return $produit->getImages();
    }
}
