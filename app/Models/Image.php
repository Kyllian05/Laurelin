<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Image';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'URL',
        'ID_PRODUIT',
    ];

    public static function get_all_images($product_id){
        return DB::select("call select_all_images($product_id)");
    }

    public static function get_one_image($product_id){
        return DB::select("call select_one_image($product_id)");
    }
}
