<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Militant;
use App\Models\Paiement;
use App\Models\Prix;
use App\Models\Operation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Ajouter un admin
     */
    public function addAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $admin = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telephone' => $request->telephone,
            'role' => 'admin',
            'is_active' => true,
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $admin->photo = $path;
            $admin->save();
        }

        // Log de l'opération
        Operation::create([
            'militant_id' => null,
            'type_operation' => 'admin_created',
            'description' => "Admin a créé un nouvel admin: {$admin->nom} {$admin->prenom}",
            'details' => json_encode(['admin_id' => $admin->id, 'created_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Admin créé avec succès',
            'admin' => $admin
        ], 201);
    }

    /**
     * Liste des admins
     */
    public function listAdmins(Request $request)
    {
        $query = User::where('role', 'admin');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        $admins = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($admins);
    }

    /**
     * Faire un paiement manuel
     */
    public function makePayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'militant_id' => 'required|exists:militants,id',
            'prix_id' => 'required|exists:prixes,id',
            'montant' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $militant = Militant::find($request->militant_id);
        $prix = Prix::find($request->prix_id);

        $paiement = Paiement::create([
            'militant_id' => $request->militant_id,
            'prix_id' => $request->prix_id,
            'montant' => $request->montant,
            'status' => 'paid',
            'date_paiement' => now(),
        ]);

        // Mettre à jour le statut de paiement du militant
        $militant->status_paiement = 'paid';
        $militant->save();

        // Log de l'opération
        Operation::create([
            'militant_id' => $militant->id,
            'type_operation' => 'manual_payment',
            'description' => "Admin a effectué un paiement manuel de {$request->montant} FCFA pour {$militant->nom} {$militant->prenom}",
            'details' => json_encode([
                'paiement_id' => $paiement->id,
                'montant' => $request->montant,
                'prix_libelle' => $prix->libelle,
                'description' => $request->description,
                'paid_by' => Auth::id()
            ])
        ]);

        return response()->json([
            'message' => 'Paiement effectué avec succès',
            'paiement' => $paiement,
            'militant' => $militant
        ]);
    }

    /**
     * Liste des demandes
     */
    public function listDemandes(Request $request)
    {
        $query = Militant::with(['user', 'circonscription', 'departement', 'commune']);

        // Filtres
        if ($request->has('status_impression')) {
            $query->where('status_impression', $request->status_impression);
        }

        if ($request->has('status_paiement')) {
            $query->where('status_paiement', $request->status_paiement);
        }

        if ($request->has('status_verification')) {
            $query->where('status_verification', $request->status_verification);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('reference_carte', 'like', "%{$search}%");
            });
        }

        $demandes = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($demandes);
    }

    /**
     * Refuser une demande
     */
    public function rejectDemande(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'motif_refus' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $militant = Militant::find($id);

        if (!$militant) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        $militant->status_verification = 'refuse';
        $militant->motif_refus = $request->motif_refus;
        $militant->save();

        // Log de l'opération
        Operation::create([
            'militant_id' => $militant->id,
            'type_operation' => 'demande_rejected',
            'description' => "Admin a refusé la demande de {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['motif' => $request->motif_refus, 'rejected_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Demande refusée avec succès',
            'militant' => $militant
        ]);
    }

    /**
     * Marquer une carte comme imprimée
     */
    public function markAsPrinted($id)
    {
        $militant = Militant::find($id);

        if (!$militant) {
            return response()->json(['message' => 'Militant non trouvé'], 404);
        }

        $militant->status_impression = 'printed';
        $militant->save();

        // Log de l'opération
        Operation::create([
            'militant_id' => $militant->id,
            'type_operation' => 'card_printed',
            'description' => "Admin a marqué la carte comme imprimée pour {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['printed_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Carte marquée comme imprimée avec succès',
            'militant' => $militant
        ]);
    }

    /**
     * Modifier le profil de l'admin
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
            'telephone' => 'sometimes|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'required_with:password|string',
            'password' => 'sometimes|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier le mot de passe actuel si changement de mot de passe
        if ($request->has('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json(['message' => 'Mot de passe actuel incorrect'], 422);
            }
            $user->password = Hash::make($request->password);
        }

        $user->fill($request->only(['nom', 'prenom', 'email', 'telephone']));

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos', 'public');
            $user->photo = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profil modifié avec succès',
            'user' => $user
        ]);
    }

    /**
     * Statistiques pour l'admin
     */
    public function getStats()
    {
        $stats = [
            'total_militants' => Militant::count(),
            'militants_actifs' => Militant::where('status', 'active')->count(),
            'militants_payes' => Militant::where('status_paiement', 'paid')->count(),
            'militants_imprimes' => Militant::where('status_impression', 'printed')->count(),
            'militants_verifies' => Militant::where('status_verification', 'correct')->count(),
            'militants_en_cours' => Militant::where('status_verification', 'en_cours')->count(),
            'militants_refuses' => Militant::where('status_verification', 'refuse')->count(),
        ];

        return response()->json($stats);
    }
}