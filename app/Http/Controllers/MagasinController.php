<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagasinController extends Controller
{
    public function list_categories_collections(Request $request){

        return Inertia::render("ListeCatCol",[
            "categories" => Categorie::all(),
            "collections" => Collection::all(),
        ]);
    }

    public function list_categories(string $name, Request $request){
        // TODO
        return response(Categorie::get_products($name), 200);
    }

    public function list_collections(string $name, Request $request){
        // TODO
        return response(Collection::get_products($name), 200);
    }
}
