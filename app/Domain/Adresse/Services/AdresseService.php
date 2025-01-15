<?php

namespace App\Domain\Adresse\Services;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Adresse\Entities\AdresseMagasin;
use App\Domain\Adresse\Entities\AdresseUtilisateur;
use App\Domain\Adresse\Entities\VilleEntity;
use App\Domain\Adresse\Repositories\AdresseRepository;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;

class AdresseService
{
    public function __construct(private AdresseRepository $adresseRepository){}

    public function add(int $numRue, string $nomRue, VilleEntity $villeEntity, UtilisateurEntity $utilisateurEntity): AdresseUtilisateur
    {
        return $this->adresseRepository->add($numRue, $nomRue, $villeEntity, $utilisateurEntity);
    }

    public function delete(AdresseUtilisateur $adresseEntity): void
    {
        $this->adresseRepository->delete($adresseEntity);
    }

    public function findById(int $id): ?AdresseUtilisateur
    {
        return $this->adresseRepository->findById($id);
    }

    public function findByIdMagasin(int $id): ?AdresseMagasin
    {
        return $this->adresseRepository->findByIdMagasin($id);
    }

    public function findMagasinByCodePostal(string $codePostal): array
    {
        return $this->adresseRepository->findMagasinByCodePostal($codePostal);
    }
}
