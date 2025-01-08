<?php

namespace App\Domain\ProductGroup\Entities;

class CategorieEntity extends ProductGroupEntity
{
    public function __construct(int $id, string $nom)
    {
        parent::__construct($id, $nom);
    }

    public function serialize(): array
    {
        return [
            "ID" => $this->getId(),
            "NOM" => $this->getNom(),
        ];
    }
}
