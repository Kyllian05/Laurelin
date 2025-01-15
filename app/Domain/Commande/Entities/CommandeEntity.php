<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Produit\Entities\ProduitEntity;
use App\Domain\Shared\Livraison;

class CommandeEntity
{
    private int $id;
    private string $date;
    private ?Livraison $livraison;
    private ?AdresseEntity $adresse;
    private array $products;
    private CommandeSate $state;

    private function __construct(int $id, string $date, array $products, CommandeSate $state, ?Livraison $livraison = null, ?AdresseEntity $adresse = null)
    {
        $this->id = $id;
        $this->date = $date;
        $this->setProducts($products);
        $this->changeSate($state);
        $this->livraison = $livraison;
        $this->adresse = $adresse;
    }
    // Factory
    public static function commandeFactory(int $id, string $date, array $products, string $etat, ?string $livraison = null, ?AdresseEntity $adresse = null): CommandeEntity
    {
        if ($livraison == "domicile") {
            $convertedLivraison = Livraison::Domicile;
        } elseif ($livraison == "magasin") {
            $convertedLivraison = Livraison::Magasin;
        } elseif (is_null($livraison)) {
            $convertedLivraison = null;
        } else {
            throw new \InvalidArgumentException("Unexpected type of livraison");
        }

        if ($etat === "Panier") {
            return new CommandeEntity($id, $date, $products, new CartSate(), $convertedLivraison, $adresse);
        } else if ($etat == "Validée") {
            return new CommandeEntity($id, $date, $products, new OrderSate(), $convertedLivraison, $adresse);
        } else if ($etat == "Expédiée" || $etat == "Terminée") {
            return new CommandeEntity($id, $date, $products, new ClosedState(), $convertedLivraison, $adresse);
        }
        throw new \InvalidArgumentException("L'état de la commande est invalide");
    }

    // Getters

    public function getId(): int
    {
        return $this->id;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function getAdresse(): ?AdresseEntity
    {
        return $this->adresse;
    }

    /** Récupère les articles de la commande
     * @return array : Un tableau de ProduitCommandeEntity
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    // Setters

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
        foreach ($products as $product) {
            if (!($product instanceof ProduitCommandeEntity)) {
                throw new \InvalidArgumentException("Le produit commandé n'est pas valide");
            }
        }
        $this->products = $products;
    }

    // Autres

    public function isPresent(ProduitEntity $produitEntity, int $taille): ?ProduitCommandeEntity
    {
        foreach ($this->products as $product) {
            if ($product->getTaille() == $taille && $product->getProduit()->id == $produitEntity->id) {
                return $product;
            }
        }
        return null;
    }

    // Serialize

    public function serialize(): array
    {
        $productsSerialized = [];

        if ($this->state instanceof CartSate) {
            foreach ($this->products as $product) {
                $productsSerialized[] = $product->serializeWithImages();
            }
        } else {
            foreach ($this->products as $product) {
                $productsSerialized[] = $product->serialize();
            }
        }

        if ($this->state instanceof CartSate) {
            $etat = "Panier";
        } else if ($this->state instanceof OrderSate) {
            $etat = "Validée";
        } else {
            $etat = "Terminée";
        }

        $livraison = "";
        if ($this->livraison == Livraison::Domicile) {
            $livraison = "domicile";
        } elseif ($this->livraison == Livraison::Magasin) {
            $livraison = "magasin";
        }

        return [
            "ID" => $this->id,
            "DATE" => $this->date,
            "LIVRAISON" => $livraison,
            "PRODUITS" => $productsSerialized,
            "ADRESSE" => $this->adresse ? $this->adresse->serialize() : "",
            "ETAT" => $etat
        ];
    }

    // State pattern

    public function changeSate(CommandeSate $state): void
    {
        $this->state = $state;
        $this->state->setContext($this);
    }

    // ---
    // Méthodes déléguées au State courant
    // ---

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

    public function toOrder(): void
    {
        $this->state->toOrder();
    }

    public function toClosed(): void
    {
        $this->state->toClosed();
    }
}
