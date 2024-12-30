<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Produit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function show(string $id, Request $request){
        if (ctype_digit($id)) {
            return Inertia::render("Produit",[
                "produit" => Produit::find($id),
                "images" => Image::get_all_images($id),
            ]);
        }
        return response("", 404);
    }
}
