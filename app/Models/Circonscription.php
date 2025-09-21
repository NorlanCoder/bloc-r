<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Circonscription extends Model
{

    protected $table = 'circonscriptions';
    protected $primaryKey = 'code_circ'; // <-- ta vraie colonne
    public $incrementing = false; // si ce n’est pas auto-incrémenté
    protected $keyType = 'string';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];
}
