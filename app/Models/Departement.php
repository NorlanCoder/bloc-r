<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departement extends Model
{

    protected $table = 'departements';
    protected $primaryKey = 'code_dep'; // <-- ta vraie colonne
    public $incrementing = false; // si ce n’est pas auto-incrémenté
    protected $keyType = 'string';
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
