<?php

namespace App\Domain\Utilisateur\Repositories;

use App\Domain\Produit\Repositories\ProduitRepository;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Models\Favoris as FavorisModel;

class FavorisRepository
{
    public function __construct(private ProduitRepository $produitRepository) {}

    public function getFavoris(UtilisateurEntity $utilisateurEntity): void
    {
        $allFavoris = FavorisModel::where("ID_UTILISATEUR", $utilisateurEntity->getId())->pluck("ID_PRODUIT")->toArray();
        $allProduitsEntities = [];
        foreach ($allFavoris as $favoris) {
            $allProduitsEntities[] = $this->produitRepository->findById($favoris);
        }
        $utilisateurEntity->setFavoris($allProduitsEntities);
    }

    /**
     * @throws \Exception : Si le produit n'existe pas
     */
    public function addFavoris(UtilisateurEntity $utilisateurEntity, ProduitEntity $produitEntity): void
    {
        // Vérifier que le produit existe
        if ($this->produitRepository->findById($produitEntity->id)) {
            $this->getFavoris($utilisateurEntity);
            // Vérifier qu'il n'est pas déjà en favoris
            if (!in_array($produitEntity, $utilisateurEntity->getFavoris())) {
                FavorisModel::create(["ID_PRODUIT"=>$produitEntity->id, "ID_UTILISATEUR"=>$utilisateurEntity->getId()]);
            }
            $this->getFavoris($utilisateurEntity);
            return;
        }
        throw new \Exception("Le produit n'existe pas");
    }

    public function deleteFavoris(UtilisateurEntity $utilisateurEntity, ProduitEntity $produitEntity): void
    {
        FavorisModel::where(["ID_PRODUIT"=>$produitEntity->id, "ID_UTILISATEUR"=>$utilisateurEntity->getId()])->delete();
        $this->getFavoris($utilisateurEntity);
    }
}
