<?php

namespace App\Models;

use App\Mail\Laurelin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

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
        $code = random_int(100000,999999);
        Code::create(["CODE"=>$code,"CODEGEN"=>date ('Y-m-d H:i:s', time()),"UTILISATEUR"=>$userId]);
        $details = [
            'title' => 'Votre code de vérification : '.$code,
            'body' => "Ce code est valable 10 minutes"
        ];

        Mail::to($user["EMAIL"])->send(new Laurelin($details));
    }

    static function isUserVerified(int $userId) : bool{
        $result = Code::where("UTILISATEUR", $userId)->first();
        if(!$result){
            return true;
        }else{
            return false;
        }
    }

    static function verifyCode(int $userId, int $code){
        Code::where("UTILISATEUR", $userId)->where(["CODE"=>$code])->delete();
    }
}
