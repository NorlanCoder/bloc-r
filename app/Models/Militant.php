<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Militant extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Scope pour filtrer par recherche de chaîne et statuts multiples.
     */
    public function scopeFilter($query, $params)
    {
        if (!empty($params['search'])) {
            $search = $params['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                    ->orWhere('prenom', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('telephone', 'like', "%$search%");
            });
        }

        if (!empty($params['status_verification'])) {
            $query->where('status_verification', $params['status_verification']);
        }

        if (!empty($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (!empty($params['status_paiement'])) {
            $query->where('status_paiement', $params['status_paiement']);
        }

        if (!empty($params['removed'])) {
            $query->where('removed', $params['removed']);
        }

        if (!empty($params['status_impression'])) {
            $query->where('status_impression', $params['status_impression']);
        }

        return $query;
    }
}
