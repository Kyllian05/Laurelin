<?php

namespace App\Domain\Commande\Repositories\Database;

use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Commande\Entities\ProduitCommandeEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ImageRepository;
use App\Domain\Produit\Repositories\ProduitRepository;
use \App\Models\Produit_Commande as ProduitCommandeModel;

class ProduitCommandeRepository
{
    public function __construct(
        private ProduitRepository $produitRepository,
        private ImageRepository $imageRepository
    ) {}

    public function findByCommandeId(int $commandeId): array
    {
        $produitsCommandeModel = ProduitCommandeModel::where("ID_COMMANDE", $commandeId)->get();
        $produits = [];
        foreach ($produitsCommandeModel as $produitCommande) {
            // Update les images
            $prod = $this->produitRepository->findById($produitCommande->ID_PRODUIT);
            $this->imageRepository->getAllProductImages($prod);
            $produits[] = new ProduitCommandeEntity(
                $produitCommande->TAILLE,
                $produitCommande->QUANTITE,
                $produitCommande->PRIX,
                $prod
            );
        }
        return $produits;
    }

    public function create(CommandeEntity $commandeEntity, ProduitCommandeEntity $produitCommandeEntity)
    {
        ProduitCommandeModel::create([
            "QUANTITE" => 1,
            "TAILLE" => $produitCommandeEntity->getTaille(),
            "ID_PRODUIT" => $produitCommandeEntity->getProduit()->id,
            "ID_COMMANDE"=> $commandeEntity->getId(),
            "PRIX" => $produitCommandeEntity->getPrix(),
        ]);
    }

    public function delete(CommandeEntity $commandeEntity, ProduitCommandeEntity $produitCommandeEntity): void
    {
        ProduitCommandeModel::where([
            "ID_COMMANDE"=>$commandeEntity->getId(),
            "ID_PRODUIT"=>$produitCommandeEntity->getProduit()->id,
            "TAILLE"=>$produitCommandeEntity->getTaille(),
        ])->delete();
    }

    public function modifyQuantity(CommandeEntity $commandeEntity, ProduitCommandeEntity $produitCommandeEntity, int $quantity): void
    {
        ProduitCommandeModel::where([
            "ID_PRODUIT" => $produitCommandeEntity->getProduit()->id,
            "ID_COMMANDE" => $commandeEntity->getId(),
            "TAILLE" => $produitCommandeEntity->getTaille(),
        ])->update(["QUANTITE"=>$quantity]);

        $produitCommandeEntity->setQuantite($quantity);
    }

    public function updatePrice(ProduitCommandeEntity $produitCommandeEntity, CommandeEntity $commandeEntity): void
    {
        $produitCommandeEntity->setPrix($produitCommandeEntity->getProduit()->prix);

        ProduitCommandeModel::where([
            "ID_PRODUIT" => $produitCommandeEntity->getProduit()->id,
            "TAILLE" => $produitCommandeEntity->getTaille(),
            "ID_COMMANDE" => $commandeEntity->getId(),
        ])->update([
            "PRIX" => $produitCommandeEntity->getPrix(),
        ]);
    }
}
