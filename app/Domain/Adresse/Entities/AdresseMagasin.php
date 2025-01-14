<?php

namespace App\Domain\Adresse\Entities;

class AdresseMagasin extends AdresseEntity
{
    public readonly string $adresse;

    public function __construct(int $id, string $adresse, VilleEntity $ville)
    {
        parent::__construct($id, $ville);
        $this->adresse = $adresse;
    }

    public function serialize(): array
    {
        return [
            'ID' => $this->id,
            'ADRESSE' => $this->adresse,
            'VILLE' => $this->ville->serialize()
        ];
    }
}
