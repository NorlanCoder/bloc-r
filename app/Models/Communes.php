<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Communes extends Model
{
    protected $table = 'communes';
    protected $primaryKey = 'code_com'; // <-- ta vraie colonne
    public $incrementing = false; // si ce n’est pas auto-incrémenté
    protected $keyType = 'string';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
