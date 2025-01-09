<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Shared\Livraison;

class CommandeEntity
{
    private int $id;
    private string $date;
    private ?Livraison $livraison;
    private ?AdresseEntity $adresse;
    private array $products;
    private CommandeSate $state;

    public function __construct(CommandeSate $sate)
    {
        // TODO
    }

    // Getters

    /** Récupère les articles de la commande
     * @return array : Un tableau de ProduitCommandeEntity
     */
    public function getProducts(): array
    {
        // TODO
        return $this->products;
    }

    // Setters

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setDate(string $date): void
    {
        $this->date = $date;
    }

    public function setLivraison(Livraison $livraison): void
    {
        $this->livraison = $livraison;
    }

    public function setAdresse(AdresseEntity $adresse): void
    {
        $this->adresse = $adresse;
    }

    public function setProducts(array $products): void
    {
        // TODO
        $this->products = $products;
    }

    // State pattern

    public function changeSate(CommandeSate $state): void
    {
        $this->state = $state;
        $this->state->setContext($this);
    }

    // Méthodes déléguées au State courant

    public function modifyQuantity(ProduitCommandeEntity $produitCommandeEntity, int $newQuantity): void
    {
        $this->state->modifyQuantity($produitCommandeEntity, $newQuantity);
    }

    public function modifyAdresse(AdresseEntity $adresseEntity): void
    {
        $this->state->modifyAdresse($adresseEntity);
    }

    public function modifyLivraison(Livraison $livraison): void
    {
        $this->state->modifyLivraison($livraison);
    }

    public function addProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        $this->state->addProduct($productCommandeEntity);
    }

    public function removeProduct(ProduitCommandeEntity $productCommandeEntity): void
    {
        $this->state->removeProduct($productCommandeEntity);
    }
}
