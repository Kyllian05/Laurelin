<?php

namespace App\Domain\Produit\Repositories;

use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Shared\ProductState;
use App\Models\Produit as ProduitModel;

class ProduitRepository
{
    public function findById(int $id): ?ProduitEntity
    {
        $produitModel = ProduitModel::find($id);

        if ($produitModel) {
            // Gestion de l'état du produit
            if ($produitModel->ETAT == "Disponible") {
                $productState = ProductState::Disponible;
            } elseif ($produitModel->ETAT == "Indisponible") {
                $productState = ProductState::Indisponible;
            } else {
                throw new \InvalidArgumentException("L'état du produit est invalide");
            }

            return new ProduitEntity(
                $produitModel->ID,
                $produitModel->NOM,
                $produitModel->MATERIAUX,
                $produitModel->DESCRIPTION,
                $produitModel->PRIX,
                $produitModel->ANNEE_CREATION,
                $productState,
                $produitModel->STOCK,
                $produitModel->ID_CATEGORIE,
            );
        }

        return null;
    }

    public function findAll(array $ids): array{
        $produitsModel = ProduitModel::whereIn('ID',$ids)->get();

        $result = [];
        foreach ($produitsModel as $produit) {
            if ($produit->ETAT == "Disponible") {
                $productState = ProductState::Disponible;
            } elseif ($produit->ETAT == "Indisponible") {
                $productState = ProductState::Indisponible;
            } else {
                throw new \InvalidArgumentException("L'état du produit est invalide");
            }

            $result[] = new ProduitEntity(
                $produit->ID,
                $produit->NOM,
                $produit->MATERIAUX,
                $produit->DESCRIPTION,
                $produit->PRIX,
                $produit->ANNEE_CREATION,
                $productState,
                $produit->STOCK,
                $produit->ID_CATEGORIE,
            );
        }
        return $result;
    }
}
