<?php

namespace App\Domain\Adresse\Entities;

class VilleEntity
{
    public readonly int $id;
    public readonly string $nom;
    public readonly int $codePostal;

    public function __construct(int $id, string $nom, int $codePostal)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->codePostal = $codePostal;
    }

    /**
     * Permet de serializé la Ville pour l'envoyer à la vue
     * @return array : Qui contient les informations de l'objet.
     */
    public function serialize(): array
    {
        return [
            'ID' => $this->id,
            'NOM' => $this->nom,
            'CODE_POSTAL' => $this->codePostal,
        ];
    }
}
