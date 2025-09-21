<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Militant extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'date_inscription' => 'datetime',
    ];

    /**
     * Relation avec l'utilisateur (agent)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec la circonscription
     */
    public function circonscription(): BelongsTo
    {
        return $this->belongsTo(Circonscription::class, 'circonscription_id', 'code_circ');
    }

    /**
     * Relation avec le département
     */
    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'code_dep');
    }

    /**
     * Relation avec la commune
     */
    public function commune(): BelongsTo
    {
        return $this->belongsTo(Communes::class, 'departement_id', 'code_com');
    }

    /**
     * Accessor pour l'URL complète de la photo
     */
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        return null;
    }

    /**
     * Scope pour filtrer par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope pour filtrer par statut de paiement
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('status_paiement', $status);
    }

    /**
     * Scope pour filtrer par statut de vérification
     */
    public function scopeByVerificationStatus($query, $status)
    {
        return $query->where('status_verification', $status);
    }

    /**
     * Scope pour rechercher par nom, prénom, email ou référence
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('nom', 'like', "%{$search}%")
              ->orWhere('prenom', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('reference_carte', 'like', "%{$search}%");
        });
    }

}
