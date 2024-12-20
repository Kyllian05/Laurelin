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

        Mail::to($user["EMAIL"])->send(new \App\Mail\EmailVerification($user["ID"],$code));
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
        $code = Code::where("UTILISATEUR", $userId)->where(["CODE"=>$code])->first();
        $genDate = ($code["CODEGEN"]);
        $currentDate = date ('Y-m-d H:i:s', time());

        $genTimestamp = strtotime($genDate);
        $currentTimestamp = strtotime($currentDate);

        if($currentTimestamp - $genTimestamp > 10*60){
            $newcode = random_int(100000,999999);
            Code::where(["UTILISATEUR"=>$userId])->update(["CODEGEN"=>date ('Y-m-d H:i:s', time()),"CODE"=>$newcode]);

            if(Code::where(["UTILISATEUR"=>$userId,"CODE"=>$newcode])->first()){
                $user = \App\Models\Utilisateur::where("ID", $userId)->first();
                Mail::to($user["EMAIL"])->send(new \App\Mail\EmailVerification($user["ID"],$newcode));
            }else{
                \Log::info("erreur lors de la regénération du code");
            }
        }else{
            Code::where(["UTILISATEUR" => $userId,"CODE"=>$code["CODE"]])->delete();
        }
    }
}
