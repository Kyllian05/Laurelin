<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Shared\Livraison;

interface CommandeSate
{
    public function setContext(CommandeEntity $newContext): void;

    public function modifyQuantity(ProduitCommandeEntity $produitCommandeEntity, int $newQuantity): void;

    public function modifyAdresse(AdresseEntity $adresseEntity): void;

    public function modifyLivraison(Livraison $livraison): void;

    public function addProduct(ProduitCommandeEntity $productCommandeEntity): void;

    public function removeProduct(ProduitCommandeEntity $productCommandeEntity): void;

    public function toOrder(): void;

    public function toClosed(): void;
}
