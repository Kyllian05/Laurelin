<?php

namespace App\Http\Controllers;

use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\Laurelin;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(Request $request){

        $current = null;
        if(session()->has('EMAIL') && session()->has('PASSWORD')){
            $current = Utilisateur::login(session('EMAIL'),session('PASSWORD'));
        }else if($request->hasCookie("TOKEN")){
            $current = Utilisateur::loginWithToken($request->cookie("TOKEN"));
        }

        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>$current["PRENOM"] ?? "",
        ]);
    }

    public function mail(Request $request){
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
