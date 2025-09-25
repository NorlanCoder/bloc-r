<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\MilitantController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;

// Routes d'authentification (publiques)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Récupérer tous les départements, circonscription, commune
Route::get('/departements', [MilitantController::class, 'getDepartements']);
Route::get('/circonscriptions', [MilitantController::class, 'getCirconscriptions']);
Route::get('/communes', [MilitantController::class, 'getCommunes']);

// Gestion des prix
Route::get('/prix', [SuperAdminController::class, 'getPrix']); // Modifier le prix

Route::put('/paiement/callback', [MilitantController::class, 'callbackPaiement']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Routes communes à tous les utilisateurs authentifiés
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::put('profile', [AuthController::class, 'updateProfile']); // Modifier le profile

    // Gestion des agents
    Route::get('/agents', [SuperAdminController::class, 'listAgents']); // Liste des agents
    Route::post('/agents/{id}/toggle', [SuperAdminController::class, 'toggleAgentStatus']); // Activer/Désactiver agent

    // Gestion des demandes
    Route::get('/demandes', [SuperAdminController::class, 'listDemandes']); // Liste des demandes
    Route::post('/demandes/{id}/reject', [SuperAdminController::class, 'rejectDemande']); // Refuser une demande
    Route::post('/demandes/{id}/print', [SuperAdminController::class, 'markAsPrinted']); // Marquer comme imprimé

    // Liste des militants
    Route::get('/militants', [SuperAdminController::class, 'listMilitants']);
    Route::get('/militants/rejecter', [SuperAdminController::class, 'listMilitantsRejected']);
    Route::get('/militants/corriger', [SuperAdminController::class, 'listMilitantsCorrected']);
    Route::get('/militants/payer', [SuperAdminController::class, 'listMilitantsPayed']);
    Route::get('/militants/impayer', [SuperAdminController::class, 'listMilitantsUnpayed']);
    Route::get('/militants/imprimer', [SuperAdminController::class, 'listMilitantsPrinted']);
    Route::get('/militants/non-imprimer', [SuperAdminController::class, 'listMilitantsNotprinted']);


    // Routes pour les agents
    Route::middleware('role:agent')->prefix('agent')->group(function () {
        Route::post('/militants', [MilitantController::class, 'store']); // Ajouter un militant
        Route::get('/militants/{id}', [MilitantController::class, 'show']); // Voir un militant
        Route::put('/militants/{id}', [MilitantController::class, 'update']); // Modifier un militant
        Route::delete('/militants/{id}', [MilitantController::class, 'destroy']);
        Route::get('/militants/agent/{agent_id}', [MilitantController::class, 'getByAgent']); // Militants d'un agent
        Route::get('/statistique', [MilitantController::class, 'getStatistique']);
    });

    // Routes pour les admins
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Gestion des admins
        Route::post('/admins', [AdminController::class, 'addAdmin']); // Ajouter un admin
        Route::get('/admins', [AdminController::class, 'listAdmins']); // Liste des admins
        
        // Paiements
        Route::post('/payments', [AdminController::class, 'makePayment']); // Paiement manuel
        
        // Profil
        Route::put('/profile', [AdminController::class, 'updateProfile']); // Modifier le profil
        
        // Statistiques
        Route::get('/statistique', [AdminController::class, 'getStats']); // Statistiques
    });

    // Routes pour les super admins
    Route::middleware('role:super_admin')->prefix('super-admin')->group(function () {
        // Gestion des admins
        Route::post('/admins', [SuperAdminController::class, 'addAdmin']); // Ajouter un admin
        Route::get('/admins', [SuperAdminController::class, 'listAdmins']); // Liste des admins
        Route::post('/admins/{id}/toggle', [SuperAdminController::class, 'toggleAdminStatus']); // Activer/Désactiver admin
        
        Route::post('/demandes/{id}/reactivate-print', [SuperAdminController::class, 'reactivateImpression']); // Réactiver impression
        
        // Gestion des prix
        Route::put('/prix', [SuperAdminController::class, 'updatePrix']); // Modifier le prix
        
        // Profil
        Route::put('/profile', [SuperAdminController::class, 'updateProfile']); // Modifier le profil
        
        // Statistiques
        Route::get('/statistique', [SuperAdminController::class, 'getStats']); // Statistiques générales
    });
});