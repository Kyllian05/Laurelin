<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PersonnalController extends Controller
{
    function index(){
        return Inertia::render("Personnal",[
            "page"=>"info",
            "info"=>[
                "Prénom"=>"pol",
                "Nom"=>"lamothe",
                "Adresse mail"=>"caca",
                "Téléphone"=>"0601"
            ],
        ]);
    }
}
