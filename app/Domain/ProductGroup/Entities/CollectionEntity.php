<?php

namespace App\Domain\ProductGroup\Entities;

class CollectionEntity extends ProductGroupEntity
{
    private int $annee;
    private string $description;

    public function __construct(int $id, string $nom, int $annee, string $description)
    {
        parent::__construct($id, $nom);
        $this->annee = $annee;
        $this->description = $description;
    }

    // Getters
    public function getAnnee(): int
    {
        return $this->annee;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function serialize(): array
    {
        return [
          "ID" => $this->getId(),
          "NOM" => $this->getNom(),
          "ANNEE" => $this->getAnnee(),
          "DESCRIPTION" => $this->getDescription(),
        ];
    }
}
