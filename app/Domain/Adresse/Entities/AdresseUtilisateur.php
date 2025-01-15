<?php

namespace App\Domain\Adresse\Entities;

class AdresseUtilisateur extends AdresseEntity
{
    public readonly int $numRue;
    public readonly string $nomRue;

    public function __construct(int $id, int $numRue, string $nomRue, VilleEntity $ville)
    {
        parent::__construct($id, $ville);
        $this->nomRue = $nomRue;
        $this->numRue = $numRue;
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
