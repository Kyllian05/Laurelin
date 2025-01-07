<?php

namespace App\Domain\Produit\Entities;

use App\Domain\Shared\ProductState;

class ProduitEntity
{
    public readonly int $id;
    public readonly string $nom;
    public readonly string $description;
    public readonly int $prix;
    public readonly int $anneeCreation;
    public readonly ProductState $etat;
    public readonly int $stock;
    public readonly array $images;
    public readonly bool $isFavorite;

    public function __construct(
        int $id,
        string $nom,
        string $description,
        int $prix,
        int $anneeCreation,
        ProductState $etat,
        int $stock,
        array $images,
        bool $isFavorite
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
        $this->prix = $prix;
        $this->anneeCreation = $anneeCreation;
        $this->etat = $etat;
        $this->stock = $stock;
        $this->images = $images;
        $this->isFavorite = $isFavorite;
    }

}
