<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Collection extends Model
{
    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'Collection';

    /**
     * Les colonnes de la table qui peuvent être assignées en masse.
     *
     * @var array
     */
    protected $fillable = [
        'NOM',
        'ANNEE',
        'DESCRIPTION'
    ];

    public static function get_products($collection_name) {
        $name = addslashes($collection_name);
        return DB::select("call select_product_collection('$name')");
    }
}
