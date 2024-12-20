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
        try{
            if(session()->has('EMAIL') && session()->has('PASSWORD')){
                $current = Utilisateur::login(session('EMAIL'),session('PASSWORD'));
            }else if($request->hasCookie("TOKEN")){
                $current = Utilisateur::loginWithToken($request->cookie("TOKEN"));
            }
        }catch(\Exception $e){
            if($e->getCode() == 517){
                $current = null;
            }else{
                throw $e;
            }
        }

        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>$current["PRENOM"] ?? "",
        ]);
    }
}
