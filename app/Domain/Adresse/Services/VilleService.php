<?php

namespace App\Domain\Adresse\Services;

use App\Domain\Adresse\Entities\VilleEntity;
use App\Domain\Adresse\Repositories\VilleRepository;

class VilleService
{
    public function __construct(private VilleRepository $villeRepository){}

    public function findByCodePostal(string $codePostal): array
    {
        return $this->villeRepository->findByCodePostal($codePostal);
    }

    public function findById(int $id): ?VilleEntity
    {
        return $this->villeRepository->findById($id);
    }
}
