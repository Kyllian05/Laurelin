<?php

namespace App\Domain\Adresse\Entities;

abstract class AdresseEntity
{
    public readonly int $id;
    public readonly VilleEntity $ville;
    private ?string $complement = null;

    public function __construct(int $id, VilleEntity $ville)
    {
        $this->id = $id;
        $this->ville = $ville;
    }

    abstract public function serialize(): array;
}
