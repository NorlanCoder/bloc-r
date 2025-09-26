<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Militant;
use App\Models\Prix;
use App\Models\Operation;
use App\Models\Impression;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $user = Auth::user();

        if (!$agent) {
            return response()->json(['message' => 'Agent non trouvé'], 404);
        }

        $agent->is_active = !$agent->is_active;
        $agent->save();

        $status = $agent->is_active ? 'activé' : 'désactivé';

        // Log de l'opération
        Operation::create([
            'militant_id' => null,
            'admin_id' => $user->id,
            'impression_id' => null,
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
            'motif_refus' => 'required|string'
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
            'admin_id' => Auth::id(),
            'impression_id' => null,
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
     * Refuser une demande
     */
    public function acceptDemande(Request $request, $id)
    {

        $militant = Militant::find($id);

        if (!$militant) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        $militant->status_verification = 'correct';
        $militant->motif_refus = '';
        $militant->save();

        // Log de l'opération
        Operation::create([
            'admin_id' => Auth::id(),
            'impression_id' => null,
            'type_operation' => 'demande_accepted',
            'description' => "Super Admin a accepté la correction de la demande de {$militant->nom} {$militant->prenom}",
            'details' => json_encode(['accepted_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Demande acceptée avec succès',
            'militant' => $militant
        ]);
    }

    /**
     * Modifier le prix de la carte
     */
    public function updatePrix(Request $request)
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

        $prix = Prix::all()->first();

        if (!$prix) {
            Prix::create(['montant' => $request->montant]);
        } else {
            $ancien_montant = $prix->montant;
            $prix->montant = $request->montant;
            $prix->save();
        }

        // Log de l'opération
        Operation::create([
            'admin_id' => Auth::id(),
            'impression_id' => null,
            'type_operation' => 'prix_updated',
            'description' => "Super Admin a modifié le de {$ancien_montant} à {$request->montant}",
            'details' => json_encode(['prix_id' => $prix->id, 'ancien_montant' => $ancien_montant, 'nouveau_montant' => $request->montant, 'updated_by' => Auth::id()])
        ]);

        return response()->json([
            'message' => 'Prix modifié avec succès',
            'prix' => $prix
        ]);
    }

    public function getPrix(Request $request) {
        $prix = Prix::all();

        return response()->json($prix);
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

        $impression = Impression::create([
            'militant_id' => $militant->id,
            'user_id' => Auth::id(),
            'date_impression' => now()
        ]);

        // Log de l'opération
        Operation::create([
            'admin_id' => $militant->id,
            'impression_id' => $impression->id,
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
        $user = Auth::user();

        $militant_femme = Militant::where('sexe', 'Femme')
                            ->get();
        $militant_homme = Militant::where('sexe', 'Homme')
                            ->get();
        $militant_impaye = Militant::where('status_paiement', 'unpaid')
                            ->get();
        $militant_paye = Militant::where('status_paiement', 'paid')
                            ->get();
        $militant_non_imprime = Militant::where('status_impression', 'not_printed')
                            ->get();
        $militant_imprime = Militant::where('status_impression', 'printed')
                            ->get();


        $departements = [
            "ALIBORI",
            "ATACORA",
            "ATLANTIQ.",
            "BORGOU",
            "COLLINES",
            "COUFFO",
            "DONGA",
            "LITTORAL",
            "MONO",
            "OUEME",
            "PLATEAU",
            "ZOU",
        ];

        $stats = Militant::select('departement_id', DB::raw('count(*) as total'))
            ->groupBy('departement_id')
            ->with('departement') // relation Eloquent
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->departement->lib_dep => $item->total];
            });

        // On formate pour s’assurer que tous les départements de la liste soient présents, même avec 0
        $result1 = collect($departements)->map(function ($dep) use ($stats) {
            return $stats[$dep] ?? 0;
        })->values()->toArray();


        $year = Carbon::now()->year;
        $stats = DB::table('militants')
            ->selectRaw('MONTH(updated_at) as mois')
            ->selectRaw("SUM(CASE WHEN status_impression = 'printed' THEN 1 ELSE 0 END) as imprimes")
            ->selectRaw("SUM(CASE WHEN status_impression = 'not_printed' THEN 1 ELSE 0 END) as non_imprimes")
            ->whereYear('updated_at', $year)
            ->groupBy('mois')
            ->get();

        // 12 mois remplis
        $imprimes = collect(range(1, 12))->map(function ($m) use ($stats) {
            return (int) optional($stats->firstWhere('mois', $m))->imprimes ?? 0;
        })->toArray();

        $nonImprimes = collect(range(1, 12))->map(function ($m) use ($stats) {
            return (int) optional($stats->firstWhere('mois', $m))->non_imprimes ?? 0;
        })->toArray();


        return response()->json([
            'success' => true,
            'militantFemme' => $militant_femme,
            'militantHomme' => $militant_homme,
            'militantPaye' => $militant_paye,
            'militantImpaye' => $militant_impaye,
            'militantImprime' => $militant_imprime,
            'militantNonImprime' => $militant_non_imprime,
            'graphique1' => $result1,
            'graphique2' => [
                'imprimes' => $imprimes,
                'nonImprimes' => $nonImprimes
            ],

        ],200);
    }

    public function listMilitants(Request $request)
    {
        $query = Militant::query();

        if ($request->has('search')) {
            $query->where('nom', 'like', '%' . $request->search . '%')
                ->orWhere('prenom', 'like', '%' . $request->search . '%');
        }

        if ($request->has('departement')) {
            $query->where('departement_id', $request->departement);
        }

        if ($request->has('circonscription')) {
            $query->where('circonscription_id', $request->circonscription);
        }

        if ($request->has('commune')) {
            $query->where('commune_id', $request->commune);
        }

        if ($request->has('status_impression')) {
            $query->where('status_impression', $request->status_impression);
        }

        if ($request->has('status_paiement')) {
            $query->where('status_paiement', $request->status_paiement);
        }

        $militants = $query->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsRejected()
    {
        $militants = Militant::where('status_verification', 'refuse')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsCorrected()
    {
        $militants = Militant::where('status_verification', 'corrige')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsPayed()
    {
        $militants = Militant::where('status_paiement', 'paid')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsUnpayed()
    {
        $militants = Militant::where('status_paiement', 'unpaid')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsPrinted()
    {
        $militants = Militant::where('status_impression', 'printed')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }

    public function listMilitantsNotprinted()
    {
        $militants = Militant::where('status_impression', 'not_printed')->get();

        return response()->json([
            'success' => true,
            'militants' => $militants,
        ],200);
    }


}
