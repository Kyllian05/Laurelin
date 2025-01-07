<?php

namespace App\Domain\Produit\Services;

use App\Domain\Shared\ProductState;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Models\Image;
use App\Models\Produit;
use App\Models\Favoris;
use App\Domain\Produit\Entities\ProduitEntity;
use http\Exception\InvalidArgumentException;

class ProduitService
{
    public function findProduit(int $id, UtilisateurEntity $user = null): ProduitEntity
    {
        // Récupération dans la base via le modèle Eloquent
        $eloquentProduit = Produit::find($id);
        $eloquentImages = Image::get_all_images($eloquentProduit->ID);
        $eloquentFavoris = false;
        if ($user) {
            $eloquentFavoris = Favoris::where(["ID_PRODUIT"=>$eloquentProduit->ID, "ID_UTILISATEUR"=>$user->id])->exists() > 0;
        }

        // Gestion de l'état du produit
        if ($eloquentProduit->ETAT == "Disponible") {
            $productState = ProductState::Disponible;
        } elseif ($eloquentProduit->ETAT == "Produit indisponible") {
            $productState = ProductState::Indisponible;
        } else {
            throw new InvalidArgumentException("L'état du produit est invalide");
        }

        // Retour d'une entité métier
        return new ProduitEntity(
            $eloquentProduit->ID,
            $eloquentProduit->NOM,
            $eloquentProduit->DESCRIPTION,
            $eloquentProduit->PRIX,
            $eloquentProduit->ANNEE_CREATION,
            $productState,
            $eloquentProduit->STOCK,
            $eloquentImages,
            $eloquentFavoris
        );
    }
}
