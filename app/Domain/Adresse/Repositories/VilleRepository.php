<?php

namespace App\Domain\Adresse\Repositories;

use App\Domain\Adresse\Entities\VilleEntity;
use \App\Models\Ville as VilleModel;

class VilleRepository
{
        public function findById(int $id): ?VilleEntity
        {
            $villeModel = VilleModel::find($id);
            if ($villeModel) {
                return new VilleEntity(
                    $villeModel->ID,
                    $villeModel->NOM,
                    $villeModel->CODE_POSTAL,
                );
            }
            return null;
        }

        public function findByCodePostal(string $codePostal): array
        {
            $villesModel = VilleModel::where('CODE_POSTAL','LIKE', $codePostal."%")->take(10)->get();
            $allVilles = [];
            foreach ($villesModel as $ville) {
                $allVilles[] = new VilleEntity(
                    $ville->ID,
                    $ville->NOM,
                    $ville->CODE_POSTAL,
                );
            }
            return $allVilles;
        }
}
