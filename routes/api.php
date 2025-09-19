<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CirconscriptionController;
use App\Http\Controllers\Api\DepartementController;
use App\Http\Controllers\Api\MilitantsController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/militantlist', [AuthController::class, 'militantList']);
    Route::post('/militantRequest', [AuthController::class, 'militantRequest']);
    Route::post('acceptMilitantRequest/{Militant}', [AuthController::class, 'acceptMilitantRequest']);
    Route::post('refuseMilitantRequest/{Militant}', [AuthController::class, 'refuseMilitantRequest']);
    Route::get('/militants', [MilitantsController::class, 'militantList']);
    Route::post('/militant-request', [MilitantsController::class, 'militantRequest']);
    Route::post('/militant-request/{militant}/accept', [MilitantsController::class, 'acceptMilitantRequest']);
    Route::post('/militant-request/{militant}/reject', [MilitantsController::class, 'rejectMilitantRequest']);
    Route::get('/militant-requests', [MilitantsController::class, 'listMilitantRequests']);
    Route::post('/militant-request/paiement', [MilitantsController::class, 'paiementRequestMilitant']);
    Route::post('/add-admin', [AdminController::class, 'addAdmin']);
    Route::get('/admins', [AdminController::class, 'listAdmins']);
    Route::post('/paiement-manuel', [AdminController::class, 'paiementManuel']);
    Route::get('/get-departements', [DepartementController::class, 'getDepartements']);
    Route::get('/get-circonscriptions', [CirconscriptionController::class, 'circonscriptions']);
    Route::get('/get-communes', [CirconscriptionController::class, 'communes']);
});
