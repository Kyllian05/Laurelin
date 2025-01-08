<?php

namespace App\Domain\ProductGroup\Entities;

use App\Domain\Produit\Entities\ProduitEntity;

abstract class ProductGroupEntity
{
    private int $id;
    private string $nom;
    private ?array $productList = null;

    public function __construct(int $id, string $nom)
    {
        $this->id = $id;
        $this->nom = $nom;
    }

    // Getters

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return array : Un tableau de ProduitEntity
     * @throws \Exception : Si la liste n'est pas initialisée
     */
    public function getProductList(): array
    {
        if (is_null($this->productList)) {
            throw new \Exception("This getter must be called from service");
        }
        return $this->productList;
    }

    // Setters

    public function setProductList(array $productList): void
    {
        // Vérifier le contenu du tableau
        foreach ($productList as $product) {
            if (!($product instanceof ProduitEntity)) {
                throw new \InvalidArgumentException("Le produit n'est pas un produit valide");
            }
        }
        $this->productList = $productList;
    }

    // Autres

    abstract function serialize(): array;
}
