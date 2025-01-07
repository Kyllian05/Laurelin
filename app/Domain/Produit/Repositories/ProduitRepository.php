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
            } elseif ($produitModel->ETAT == "Produit indisponible") {
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
            );
        }

        return null;
    }
}
