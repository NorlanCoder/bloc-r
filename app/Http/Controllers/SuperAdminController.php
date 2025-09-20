<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Militant;
use App\Models\Prix;
use App\Models\Operation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
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
            'description' => "Super Admin a créé un nouvel admin: {$admin->nom} {$admin->prenom}",
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
     * Activer/Désactiver un admin
     */
    public function toggleAdminStatus($id)
    {
        $admin = User::where('id', $id)->where('role', 'admin')->first();

        if (!$admin) {
            return response()->json(['message' => 'Admin non trouvé'], 404);
        }

        $admin->is_active = !$admin->is_active;
        $admin->save();

        $status = $admin->is_active ? 'activé' : 'désactivé';

        // Log de l'opération
        Operation::create([
            'militant_id' => null,
            'type_operation' => 'admin_status_changed',
            'description' => "Super Admin a {$status} l'admin: {$admin->nom} {$admin->prenom}",
            'details' => json_encode(['admin_id' => $admin->id, 'new_status' => $admin->is_active, 'changed_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => "Admin {$status} avec succès",
            'admin' => $admin
        ]);
    }

    /**
     * Liste des agents
     */
    public function listAgents(Request $request)
    {
        $query = User::where('role', 'agent');

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

        $agents = $query->withCount('militants')->orderBy('created_at', 'desc')->paginate(15);

        return response()->json($agents);
    }

    /**
     * Activer/Désactiver un agent
     */
    public function toggleAgentStatus($id)
    {
        $agent = User::where('id', $id)->where('role', 'agent')->first();

        if (!$agent) {
            return response()->json(['message' => 'Agent non trouvé'], 404);
        }

        $agent->is_active = !$agent->is_active;
        $agent->save();

        $status = $agent->is_active ? 'activé' : 'désactivé';

        // Log de l'opération
        Operation::create([
            'militant_id' => null,
            'type_operation' => 'agent_status_changed',
            'description' => "Super Admin a {$status} l'agent: {$agent->nom} {$agent->prenom}",
            'details' => json_encode(['agent_id' => $agent->id, 'new_status' => $agent->is_active, 'changed_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => "Agent {$status} avec succès",
            'agent' => $agent
        ]);
    }

    /**
     * Liste des demandes avec filtres
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
            'description' => "Super Admin a refusé la demande de {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['motif' => $request->motif_refus, 'rejected_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Demande refusée avec succès',
            'militant' => $militant
        ]);
    }

    /**
     * Modifier le prix de la carte
     */
    public function updatePrix(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'montant' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $prix = Prix::find($id);

        if (!$prix) {
            return response()->json(['message' => 'Prix non trouvé'], 404);
        }

        $ancien_montant = $prix->montant;
        $prix->montant = $request->montant;
        $prix->save();

        // Log de l'opération
        Operation::create([
            'militant_id' => null,
            'type_operation' => 'prix_updated',
            'description' => "Super Admin a modifié le prix de {$prix->libelle} de {$ancien_montant} à {$request->montant}",
            'details' => json_encode(['prix_id' => $prix->id, 'ancien_montant' => $ancien_montant, 'nouveau_montant' => $request->montant, 'updated_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Prix modifié avec succès',
            'prix' => $prix
        ]);
    }

    /**
     * Réactiver l'impression d'une carte
     */
    public function reactivateImpression($id)
    {
        $militant = Militant::find($id);

        if (!$militant) {
            return response()->json(['message' => 'Militant non trouvé'], 404);
        }

        $militant->status_impression = 'not_printed';
        $militant->save();

        // Log de l'opération
        Operation::create([
            'militant_id' => $militant->id,
            'type_operation' => 'impression_reactivated',
            'description' => "Super Admin a réactivé l'impression pour {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['reactivated_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Impression réactivée avec succès',
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
            'description' => "Super Admin a marqué la carte comme imprimée pour {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['printed_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Carte marquée comme imprimée avec succès',
            'militant' => $militant
        ]);
    }

    /**
     * Modifier le profil du super admin
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
     * Statistiques générales
     */
    public function getStats()
    {
        $stats = [
            'total_militants' => Militant::count(),
            'militants_actifs' => Militant::where('status', 'active')->count(),
            'militants_payes' => Militant::where('status_paiement', 'paid')->count(),
            'militants_imprimes' => Militant::where('status_impression', 'printed')->count(),
            'militants_verifies' => Militant::where('status_verification', 'correct')->count(),
            'total_agents' => User::where('role', 'agent')->count(),
            'agents_actifs' => User::where('role', 'agent')->where('is_active', true)->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'admins_actifs' => User::where('role', 'admin')->where('is_active', true)->count(),
        ];

        return response()->json($stats);
    }
}
