<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Code';

    public $timestamps = false;

    protected $fillable = [
        "CODE",
        "CODEGEN",
        "UTILISATEUR"
    ];

    static function generateCode(int $userId){
        $user = \App\Models\Utilisateur::where("ID", $userId)->first();
        Code::create(["CODE"=>random_int(100000,999999),"CODEGEN"=>date ('Y-m-d H:i:s', time()),"UTILISATEUR"=>$userId]);

    }
}
