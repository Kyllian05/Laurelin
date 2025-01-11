<?php

namespace App\Domain\Adresse\Entities;

class AdresseEntity
{
    public readonly int $id;
    public readonly int $numRue;
    public readonly string $nomRue;
    public readonly VilleEntity $ville;
    private ?string $complement = null;

    public function __construct(int $id, int $numRue, string $nomRue, VilleEntity $ville)
    {
        $this->id = $id;
        $this->numRue = $numRue;
        $this->nomRue = $nomRue;
        $this->ville = $ville;
    }

    public function serialize(): array
    {
        return [
            'ID' => $this->id,
            'NUM_RUE' => $this->numRue,
            'NOM_RUE' => $this->nomRue,
            'VILLE' => $this->ville->serialize()
        ];
    }
}
