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
        if(session()->has('email') && session()->has('password')){
            $current = Utilisateur::login(session('email'),session('password'));
        }else if($request->hasCookie("token")){
            $current = Utilisateur::loginWithToken($request->cookie("token"));
        }

        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>$current->prenom ?? "",
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
