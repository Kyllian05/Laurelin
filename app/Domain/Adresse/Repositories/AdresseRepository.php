<?php

namespace App\Domain\Adresse\Repositories;

use App\Domain\Adresse\Entities\AdresseMagasin;
use App\Domain\Adresse\Entities\AdresseUtilisateur;
use App\Domain\Adresse\Entities\VilleEntity;
use App\Domain\Utilisateur\Entities\UtilisateurEntity;
use App\Models\Adresse as AdresseModel;
use App\Models\AdresseMagasins as AdresseMagasinModel;

class AdresseRepository
{
    public function __construct(
        private VilleRepository $villeRepository
    ){}

    public function findById(int $id): ?AdresseUtilisateur
    {
        $adresseModel = AdresseModel::find($id);
        if ($adresseModel) {
            return new AdresseUtilisateur(
                $adresseModel->ID,
                $adresseModel->NUM_RUE,
                $adresseModel->NOM_RUE,
                $this->villeRepository->findById($adresseModel->ID_VILLE),
            );
        }
        return null;
    }


    public function findByIdMagasin(int $id): ?AdresseMagasin
    {
        $adresseModel = AdresseMagasinModel::find($id);
        if ($adresseModel) {
            return new AdresseMagasin(
                $adresseModel->ID,
                $adresseModel->ADRESSE,
                $this->villeRepository->findById($adresseModel->ID_VILLE),
            );
        }
        return null;
    }

    public function findMagasinByCodePostal(string $codePostal): array
    {
        $villes = $this->villeRepository->findByCodePostal($codePostal, false);
        $villesIds = [];
        foreach ($villes as $ville) {
            $villesIds[] = $ville->id;
        }
        $adresseModel = AdresseMagasinModel::whereIn('ID_VILLE', $villesIds)->get();
        $allAdresses = [];
        foreach ($adresseModel as $adresse) {
            $index = array_search($adresse->ID_VILLE, $villesIds);
            $allAdresses[] = new AdresseMagasin(
                $adresse->ID,
                $adresse->ADRESSE,
                $villes[$index]
            );
        }
        return $allAdresses;
    }

    public function findByIds(array $ids): array
    {
        $adressesModel = AdresseModel::whereIn('ID', $ids)->get();

        $villesId = $adressesModel->pluck('ID_VILLE')->unique();

        $villes = $this->villeRepository->findByIds($villesId->toArray());

        $villesIndexees = [];
        foreach ($villes as $ville) {
            $villesIndexees[$ville->id] = $ville;
        }

        $adresses = [];
        foreach ($adressesModel as $adresseModel) {
            $adresses[] = new AdresseUtilisateur(
                $adresseModel->ID,
                $adresseModel->NUM_RUE,
                $adresseModel->NOM_RUE,
                $villesIndexees[$adresseModel->ID_VILLE],
            );
        }
        return $adresses;
    }

    public function findMagasinByIds(array $ids): array
    {
        $adressesModel = AdresseMagasinModel::whereIn('ID', $ids)->get();
        $villesId = $adressesModel->pluck('ID_VILLE')->unique();

        $villes = $this->villeRepository->findByIds($villesId->toArray());

        $villesIndexees = [];
        foreach ($villes as $ville) {
            $villesIndexees[$ville->id] = $ville;
        }

        $adresses = [];
        foreach ($adressesModel as $adresseModel) {
            $adresses[] = new AdresseMagasin(
                $adresseModel->ID,
                $adresseModel->ADRESSE,
                $villesIndexees[$adresseModel->ID_VILLE],
            );
        }
        return $adresses;
    }

    public function add(int $numRue, string $nomRue, VilleEntity $villeEntity, UtilisateurEntity $utilisateurEntity): AdresseUtilisateur
    {
        $adresseModel = AdresseModel::create(["NUM_RUE"=>$numRue,"NOM_RUE"=>$nomRue,"ID_UTILISATEUR"=>$utilisateurEntity->getId(),"ID_VILLE"=>$villeEntity->id]);

        return new AdresseUtilisateur(
            $adresseModel->ID,
            $adresseModel->NUM_RUE,
            $adresseModel->NOM_RUE,
            $villeEntity
        );
    }

    public function delete(AdresseUtilisateur $adresseEntity): void
    {
        AdresseModel::where(["ID"=>$adresseEntity->id])->delete();
    }

    public function findByUser(UtilisateurEntity $user): array
    {
        $adressesModel = AdresseModel::where("ID_UTILISATEUR", $user->getId())->get();

        $villesId = [];
        foreach ($adressesModel as $adresseModel) {
            $villesId[] = $adresseModel->ID_VILLE;
        }

        $villes = $this->villeRepository->findByIds($villesId);

        $adressesville = [];

        for($i = 0; $i < sizeof($adressesModel); $i++){
            for($j = 0; $j < sizeof($villes); $j++){
                if($adressesModel[$i]->ID_VILLE == $villes[$j]->id){
                    $adressesville[$adressesModel[$i]["ID"]] = $villes[$j];
                    break;
                }
            }
        }

        $adresses = [];
        foreach ($adressesModel as $adresseModel) {
            $adresses[] = new AdresseUtilisateur(
                $adresseModel->ID,
                $adresseModel->NUM_RUE,
                $adresseModel->NOM_RUE,
                $adressesville[$adresseModel->ID],
            );
        }
        return $adresses;
    }
}
