<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Shared\Livraison;

class CartSate implements CommandeSate
{
    private CommandeEntity $context;

    public function setContext(CommandeEntity $newContext): void
    {
        $this->context = $newContext;
    }

    public function modifyQuantity(ProduitCommandeEntity $produitCommandeEntity, int $newQuantity): void
    {
        $produitCommandeEntity->setQuantite($newQuantity);
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

    public function addProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        $products = $this->context->getProducts();
        $products[] = $productCommandeEntity;
        $this->context->setProducts($products);
    }

    public function removeProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        $products = $this->context->getProducts();
        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i] == $productCommandeEntity) {
                unset($products[$i]);
            }
        }
        $this->context->setProducts($products);
    }
}
