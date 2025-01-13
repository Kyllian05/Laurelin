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
        $produitsID = [];
        foreach ($produitsCommandeModel as $produitCommande) {
            $produitsID[] = $produitCommande["ID_PRODUIT"];
        }

        $produits = $this->produitRepository->findAll($produitsID);
        $result = [];

        //$this->imageRepository->getAllProductsImages($produits);

        for($i = 0; $i < sizeof($produitsID); $i++) {
            $result[] = new ProduitCommandeEntity(
                $produitsCommandeModel[$i]->TAILLE,
                $produitsCommandeModel[$i]->QUANTITE,
                $produitsCommandeModel[$i]->PRIX,
                $produits[$i]
            );
        }
        return $result;
    }

    public function findByCommandesIds(array $commandesIds): array{
        $produitsCommandesModel = ProduitCommandeModel::whereIn("ID_COMMANDE", $commandesIds)->get();

        $produitsID = [];//List des produits à récupérer
        foreach ($produitsCommandesModel as $produitCommande) {
            $produitsID[] = $produitCommande["ID_PRODUIT"];
        }

        $produits = $this->produitRepository->findAll($produitsID);
        $produitsTreated = [];
        for($i = 0; $i < sizeof($produits); $i++) {
            $produitsTreated[$produits[$i]->id] = $produits[$i];
        }

        $result = [];
        for($i = 0; $i < sizeof($produitsCommandesModel); $i++) {
            $result[] = new ProduitCommandeEntity(
                $produitsCommandesModel[$i]->TAILLE,
                $produitsCommandesModel[$i]->QUANTITE,
                $produitsCommandesModel[$i]->PRIX,
                $produitsTreated[$produitsCommandesModel[$i]->ID_PRODUIT],
                $produitsCommandesModel[$i]->ID_COMMANDE
            );
        }
        return $result;
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
