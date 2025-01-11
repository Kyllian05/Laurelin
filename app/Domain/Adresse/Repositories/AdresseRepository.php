<?php

namespace App\Domain\Adresse\Repositories;

use App\Domain\Adresse\Entities\AdresseEntity;
use App\Domain\Adresse\Entities\VilleEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Models\Adresse as AdresseModel;

class AdresseRepository
{
    public function __construct(
        private VilleRepository $villeRepository
    ){}

    public function findById(int $id): ?AdresseEntity
    {
        $adresseModel = AdresseModel::find($id);
        if ($adresseModel) {
            return new AdresseEntity(
                $adresseModel->ID,
                $adresseModel->NUM_RUE,
                $adresseModel->NOM_RUE,
                $this->villeRepository->findById($adresseModel->ID_VILLE),
            );
        }
        return null;
    }

    public function add(int $numRue, string $nomRue, VilleEntity $villeEntity, UtilisateurEntity $utilisateurEntity): AdresseEntity
    {
        $adresseModel = AdresseModel::create(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"ID_UTILISATEUR"=>$utilisateurEntity->getId(),"ID_VILLE"=>$villeEntity->id]);

        return new AdresseEntity(
            $adresseModel->ID,
            $adresseModel->NUM_RUE,
            $adresseModel->NOM_RUE,
            $villeEntity
        );
    }

    public function delete(AdresseEntity $adresseEntity): void
    {
        AdresseModel::where(["ID"=>$adresseEntity->id])->delete();
    }

    public function findByUser(UtilisateurEntity $user): array
    {
        $adressesModel = AdresseModel::where("ID_UTILISATEUR", $user->getId())->get();
        $adresses = [];
        foreach ($adressesModel as $adresseModel) {
            $adresses[] = new AdresseEntity(
                $adresseModel->ID,
                $adresseModel->NUM_RUE,
                $adresseModel->NOM_RUE,
                $this->villeRepository->findById($adresseModel->ID_VILLE),
            );
        }
        return $adresses;
    }
}
