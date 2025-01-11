<?php

namespace App\Domain\Adresse\Services;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Adresse\Entities\VilleEntity;
use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class AdresseService
{
    public function __construct(private AdresseRepository $adresseRepository){}

    public function add(int $numRue, string $nomRue, VilleEntity $villeEntity, UtilisateurEntity $utilisateurEntity): AdresseEntity
    {
        return $this->adresseRepository->add($numRue, $nomRue, $villeEntity, $utilisateurEntity);
    }

    public function delete(AdresseEntity $adresseEntity): void
    {
        $this->adresseRepository->delete($adresseEntity);
    }

    public function findById(int $id): ?AdresseEntity
    {
        return $this->adresseRepository->findById($id);
    }
}
