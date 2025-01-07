<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favoris extends Model
{
    protected $table = 'Favoris';
    protected $fillable = ['ID_PRODUIT', 'ID_UTILISATEUR'];
    public $timestamps = false;
}
