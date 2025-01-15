<?php

namespace App\Domain\ProductGroup\Repositories;

use App\Domain\ProductGroup\Entities\CollectionEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ProduitRepository;
use App\Models\Collection as CollectionModel;
use App\Models\Produit as ProduitModel;

class CollectionRepository
{
    public function __construct(private ProduitRepository $produitRepository) {}

    public function getAll(): array
    {
        $collectionsModel =  CollectionModel::all();
        $allCollections = [];
        foreach ($collectionsModel as $collection) {
            $allCollections[] = new CollectionEntity(
                $collection->ID,
                $collection->NOM,
                $collection->ANNEE,
                $collection->DESCRIPTION
            );
        }
        return $allCollections;
    }

    public function findByName(string $name): ?CollectionEntity
    {
        $collectionModel =  CollectionModel::where('NOM', $name)->first();
        if ($collectionModel) {
            return new CollectionEntity(
                $collectionModel->ID,
                $collectionModel->NOM,
                $collectionModel->ANNEE,
                $collectionModel->DESCRIPTION
            );
        }
        return null;
    }

    public function findById(int $id): ?CollectionEntity
    {
        $collectionModel =  CollectionModel::where('ID', $id)->first();
        if ($collectionModel) {
            return new CollectionEntity(
                $collectionModel->ID,
                $collectionModel->NOM,
                $collectionModel->ANNEE,
                $collectionModel->DESCRIPTION
            );
        }
        return null;
    }

    public function getProducts(CollectionEntity $collectionEntity): void
    {
        $productsModel = ProduitModel::where("ID_COLLECTION", $collectionEntity->getId())->pluck("ID")->toArray();
        $allProducts = [];
        foreach ($productsModel as $product) {
            $allProducts[] = $this->produitRepository->findById($product);
        }
        $collectionEntity->setProductList($allProducts);
    }

    public function findByProduct(ProduitEntity $produitEntity): ?CollectionEntity
    {
        $produitModel = ProduitModel::find($produitEntity->id);
        return $this->findById($produitModel->ID_COLLECTION);
    }
}
