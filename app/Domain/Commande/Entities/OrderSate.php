<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Shared\Livraison;

class OrderSate implements CommandeSate
{
    private CommandeEntity $context;

    public function setContext(CommandeEntity $newContext): void
    {
        $this->context = $newContext;
    }

    /**
     * @throws \Exception : La quantité ne peut plus être modifiée
     */
    public function modifyQuantity(ProduitCommandeEntity $produitCommandeEntity, int $newQuantity): void
    {
        throw new \Exception("La quantité du produit ne peut plus être modifiée à ce stade");
    }

    public function modifyAdresse(AdresseEntity $adresseEntity): void
    {
        $this->context->setAdresse($adresseEntity);
    }

    public function modifyLivraison(Livraison $livraison): void
    {
        $this->context->setLivraison($livraison);
    }

    /**
     * @throws \Exception
     */
    public function addProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        throw new \Exception("Il n'est plus possible d'ajouter un produit dans cette commande");
    }

    /**
     * @throws \Exception
     */
    public function removeProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        throw new \Exception("Il n'est plus possible de supprimer un produit dans cette commande");
    }
}
