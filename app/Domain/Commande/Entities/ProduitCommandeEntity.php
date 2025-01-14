<?php

namespace App\Domain\Commande\Entities;

use App\Domain\Produit\Entities\ProduitEntity;

class ProduitCommandeEntity
{
    private ?int $taille;
    private int $quantite;
    private ?int $prix;
    private ProduitEntity $produit;

    private ?int $commandeID;

    public function __construct(int $taille, int $quantite, ?int $prix, ProduitEntity $produit, ?int $commandeID)
    {
        $this->taille = $taille;
        $this->quantite = $quantite;
        $this->produit = $produit;
        $this->prix = $prix;
        $this->commandeID = $commandeID;
    }

    // Getters

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function getProduit(): ProduitEntity
    {
        return $this->produit;
    }

    // Setters

    public function setTaille(int $taille): void
    {
        $this->taille = $taille;
    }

    public function setQuantite(int $quantite): void
    {
        if ($quantite < 0) {
            throw new \InvalidArgumentException("La quantité ne peut pas être inférieure à 0.");
        }
        $this->quantite = $quantite;
    }

    public function setPrix(int $prix): void
    {
        $this->prix = $prix;
    }

    public function setProduit(ProduitEntity $produit): void
    {
        $this->produit = $produit;
    }

    public function setCommandeID(int $commandeID): void{
        $this->commandeID = $commandeID;
    }

    public function getCommandeID(): int{
        return $this->commandeID;
    }

    public function serialize(): array
    {
        return [
            'TAILLE' => $this->taille,
            'QUANTITE' => $this->quantite,
            'PRIX' => $this->prix,
            'PRODUIT' => $this->produit->serializeWithoutImages(),
        ];
    }

    public function serializeWithImages(): array
    {
        return [
            'TAILLE' => $this->taille,
            'QUANTITE' => $this->quantite,
            'PRIX' => $this->prix,
            'PRODUIT' => $this->produit->serialize(),
        ];
    }
}
