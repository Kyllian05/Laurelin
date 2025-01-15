<?php

namespace App\Domain\Commande\Service;

use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Adresse\Repositories\VilleRepository;
use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Commande\Repositories\CommandeRepositoryInterface;
use App\Domain\Commande\Repositories\Database\CommandeRepository as DBCommandeRepository;
use App\Domain\Commande\Repositories\Database\ProduitCommandeRepository as DBProduitCommandeRepository;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Produit\Repositories\ImageRepository;
use App\Domain\Produit\Repositories\ProduitRepository;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class CartService
{
    private CommandeRepositoryInterface $commandeRepository;

    public function __construct(?UtilisateurEntity $utilisateur = null)
    {
        $imageRepo = new ImageRepository();
        if ($utilisateur) {
            $this->commandeRepository = new DBCommandeRepository(
                new DBProduitCommandeRepository(new ProduitRepository(), $imageRepo),
                new AdresseRepository(new VilleRepository()),
                $imageRepo
            );
        } else {
            throw new \Exception("TODO : panier non-connecté");
        }
    }

    public function getCart(?UtilisateurEntity $user) : CommandeEntity
    {
        return $this->commandeRepository->getCart($user);
    }

    public function addProduct(CommandeEntity $commande, ProduitEntity $produitEntity, int $taille = 0): void
    {
        $produitCommande = $commande->isPresent($produitEntity, $taille);
        if ($produitCommande) {
            $this->commandeRepository->modifyQuantity($commande, $produitCommande, $produitCommande->getQuantite()+1);
        } else {
            $this->commandeRepository->addProduct($commande, $produitEntity, $taille, null);
        }
    }

    /**
     * @throws \Exception : Si le produit n'est pas dans le panier
     */
    public function removeProduct(CommandeEntity $commande, ProduitEntity $produitEntity, int $taille = 0): void
    {
        $produitCommande = $commande->isPresent($produitEntity, $taille);
        if (is_null($produitCommande)) {
            throw new \Exception("Le produit n'est pas dans le panier");
        }
        if ($produitCommande->getQuantite() >= 2) {
            $this->commandeRepository->modifyQuantity($commande, $produitCommande, $produitCommande->getQuantite()-1);
        } else {
            $this->commandeRepository->removeProduct($commande, $produitCommande);
        }
    }
}
