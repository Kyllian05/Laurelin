<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeController extends Controller
{
    public function index($params){
        $urlData = null;
        if(sizeof($params) > 1){
            $urlData = $params[1];
        }
        return Inertia::render("Home",[
            "test"=>"coucou2",
            "urlData"=>$urlData
        ]);
    }
}
