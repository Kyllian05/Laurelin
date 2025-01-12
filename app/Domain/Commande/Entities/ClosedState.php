<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Shared\Livraison;

class ClosedState implements CommandeSate
{
    private CommandeEntity $context;

    public function setContext(CommandeEntity $newContext): void
    {
        $this->context = $newContext;
    }

    public function toOrder(): void
    {
        throw new \Exception("Impossible de changer d'état.");
    }

    public function toClosed(): void
    {
        throw new \Exception("La commande est déjà dans cet état.");
    }

    /**
     * @throws \Exception : La quantité ne peut plus être modifiée
     */
    public function modifyQuantity(ProduitCommandeEntity $produitCommandeEntity, int $newQuantity): void
    {
        throw new \Exception("La quantité du produit ne peut plus être modifiée à ce stade");
    }

    /**
     * @throws \Exception : L'adresse ne peut pas être modifiée
     */
    public function modifyAdresse(AdresseEntity $adresseEntity): void
    {
        throw new \Exception("L'adresse ne peut pas être modifiée à ce stade.");
    }

    /**
     * @throws \Exception : La livraison ne peut pas être modifiée
     */
    public function modifyLivraison(Livraison $livraison): void
    {
        throw new \Exception("La livraison ne peut pas être modifiée à ce stade.");
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
