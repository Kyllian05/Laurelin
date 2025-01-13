<?php

namespace App\Domain\Produit\Repositories;

use App\Domain\Produit\Entities\ProduitEntity;
use App\Models\Image as ImageModel;

class ImageRepository
{
    public function getAllProductImages(ProduitEntity $produitEntity)
    {
        $imagesModel = ImageModel::where("ID_PRODUIT", $produitEntity->id)->pluck("URL")->toArray();
        if (!empty($imagesModel)) {
            $produitEntity->setImages($imagesModel);
        }
    }
}
