<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Mail\Laurelin;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(array $params){
        $urlData = null;
        if(sizeof($params) > 0){
            $urlData = $params[0];
        }
        return Inertia::render("Home",[
            "test"=>"coucou2",
            "urlData"=>$urlData
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
