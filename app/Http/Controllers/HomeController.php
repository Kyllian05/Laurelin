<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

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
}
