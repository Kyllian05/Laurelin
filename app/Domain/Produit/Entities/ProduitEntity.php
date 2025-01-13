<?php

namespace App\Domain\Produit\Entities;

use App\Domain\Shared\ProductState;

class ProduitEntity
{
    public readonly int $id;
    public readonly string $nom;
    public readonly ?string $materiaux;
    public readonly string $description;
    public readonly float $prix;
    public readonly int $anneeCreation;
    public readonly ProductState $etat;
    public readonly int $stock;
    private ?array $images = null;

    public function __construct(
        int $id,
        string $nom,
        ?string $materiaux,
        string $description,
        float $prix,
        int $anneeCreation,
        ProductState $etat,
        int $stock,
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->materiaux = $materiaux;
        $this->description = $description;
        $this->prix = $prix;
        $this->anneeCreation = $anneeCreation;
        $this->etat = $etat;
        $this->stock = $stock;
    }

    // Getters

    /**
     * @throws \Exception : Si les images ne sont pas initialisées
     */
    public function getImages(): array
    {
        if (is_null($this->images)) {
            throw new \Exception("Get must be called from ProduitService");
        }
        return $this->images;
    }

    // Setters
    public function setImages(array $images): void
    {
        foreach ($images as $image) {
            if (!is_string($image)) {
                throw new \InvalidArgumentException('Les images doivent être des chaînes de caractères contenant leur URL');
            }
        }
        $this->images = $images;
    }

    // Autres

    public function serialize(): array
    {
        return [
            "ID" => $this->id,
            "NOM" => $this->nom,
            "MATERIAUX" => $this->materiaux,
            "DESCRIPTION" => $this->description,
            "PRIX" => $this->prix,
            "ANNEE_CREATION" => $this->anneeCreation,
            "STOCK" => $this->stock,
            "IMAGES" => $this->getImages()
        ];
    }
}
