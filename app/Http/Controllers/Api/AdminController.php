<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function addAdmin(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut ajouter un autre admin.'], 403);
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'photo' => $request->photo,
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Admin ajouté avec succès',
            'user' => $user,
        ]);
    }

    public function listAdmins(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }

        $admins = User::where('role', 'admin')->get();

        return response()->json([
            'success' => true,
            'admins' => $admins,
        ]);
    }

    public function paiementManuel(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user->isAdmin() || $user->isAgent())) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $request->validate([
            'militant_id' => 'required|integer|exists:militants,id',
            'montant' => 'required|numeric|min:0',
            'mode' => 'required|string|max:50',
        ]);

        // Exemple d'enregistrement du paiement
        $paiement = Paiement::create([
            'militant_id' => $request->militant_id,
            'montant' => $request->montant,
            'mode' => $request->mode,
            'effectue_par' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Paiement manuel enregistré avec succès',
            'paiement' => $paiement,
        ]);
    }

    
}
