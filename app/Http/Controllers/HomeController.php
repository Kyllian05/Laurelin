<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Inertia\Inertia;
use App\Mail\Laurelin;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(){
        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>session("prenom","alo")
        ]);
    }

    public function mail(){
        $details = [
            'title' => 'Titre de l’e-mail',
            'body' => 'Contenu de l’e-mail.'
        ];

        Mail::to('MAILTEST')->send(new Laurelin($details));
        return Inertia::render("Home",[
            "test"=>"coucou2"
        ]);
    }
}
