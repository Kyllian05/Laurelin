<?php

namespace App\Domain\Commande\Repositories;

use App\Domain\Commande\Entities\CommandeEntity;
use App\Domain\Commande\Entities\ProduitCommandeEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

interface CommandeRepositoryInterface
{
    public function getCart(?UtilisateurEntity $user) : CommandeEntity;

    public function addProduct(CommandeEntity $commande, ProduitEntity $produitEntity, int $taille, ?int $prix);

    public function removeProduct(CommandeEntity $commande, ProduitCommandeEntity $produitCommandeEntity);

    public function modifyQuantity(CommandeEntity $commande, ProduitCommandeEntity $produitCommandeEntity, int $quantity);
}
