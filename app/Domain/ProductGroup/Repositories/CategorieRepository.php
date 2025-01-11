<?php

namespace App\Domain\ProductGroup\Repositories;

use App\Domain\ProductGroup\Entities\CategorieEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ProduitRepository;
use App\Models\Categorie as CategorieModel;
use App\Models\Produit as ProduitModel;

class CategorieRepository
{
    public function __construct(private ProduitRepository $produitRepository) {}

    public function getAll(): array
    {
        $categoriesModel =  CategorieModel::all();
        $allCategories = [];
        foreach ($categoriesModel as $category) {
            $allCategories[] = new CategorieEntity(
                $category->ID,
                $category->NOM
            );
        }
        return $allCategories;
    }

    public function findByName(string $name): ?CategorieEntity
    {
        $categoriesModel =  CategorieModel::where('NOM', $name)->first();
        if ($categoriesModel) {
            return new CategorieEntity(
                $categoriesModel->ID,
                $categoriesModel->NOM
            );
        }
        return null;
    }

    public function findById(int $id): ?CategorieEntity
    {
        $categoriesModel =  CategorieModel::where('ID', $id)->first();
        if ($categoriesModel) {
            return new CategorieEntity(
                $categoriesModel->ID,
                $categoriesModel->NOM
            );
        }
        return null;
    }

    public function getProducts(CategorieEntity $category): void
    {
        $productsModel = ProduitModel::where("ID_CATEGORIE", $category->getId())->pluck("ID")->toArray();
        $allProducts = [];
        foreach ($productsModel as $product) {
            $allProducts[] = $this->produitRepository->findById($product);
        }
        $category->setProductList($allProducts);
    }

    public function findByProduct(ProduitEntity $produitEntity): ?CategorieEntity
    {
        $produitModel = ProduitModel::find($produitEntity->id);
        return $this->findById($produitModel->ID_CATEGORIE);
    }
}
