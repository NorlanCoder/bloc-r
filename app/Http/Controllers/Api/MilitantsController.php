<?php

namespace App\Http\Controllers\Api;

use App\Models\Militant;
use App\Models\Paiement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class MilitantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function militantList(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }

        $militants = Militant::where('status_verification', 'correct')->filter($request->all())->get();
        return response()->json($militants);
    }


    /**
     * creating a new resource.
     */
    public function militantRequest(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:militants',
            'telephone' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|string|max:255',
            'user_id' => 'required|integer|exists:users,id',
            'circonscription_id' => 'required|integer|exists:circonscriptions',
            'departement_id' => 'required|integer|exists:departements',
            'commune_id' => 'required|integer|exists:communes',
            'reference_carte' => 'required|integer|max:255',
            'status_paiement' => 'required|string|max:255',
            'removed' => 'nullable|string|max:10',
            'motif_refus' => 'nullable|string|max:255',
            'status_impression' => 'required|string|max:255',
            'status_verification' => 'required|string|max:255',
        ]);

        $militant = Militant::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'user_id' => $request->user_id,
            'circonscription_id' => $request->circonscription_id,
            'departement_id' => $request->departement_id,
            'commune_id' => $request->commune_id,
            'reference_carte' => $request->reference_carte,
            'status_paiement' => $request->status_paiement,
            'removed' => $request->removed,
            'motif_refus' => $request->motif_refus,
            'status_impression' => $request->status_impression,
            'status_verification' => $request->status_verification
        ]);

        return response()->json([
            'success' => true,
            'militante' => $militant
        ]);
    }

    /**
     * accept a request.
     */
    public function acceptMilitantRequest(Request $request, Militant $militant)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }

        $militant->status_verification = 'correct';
        $militant->save();

        return response()->json([
            'success' => true,
            'militant' => $militant
        ]);
    }

    /**
     * rejet a request.
     */
    public function rejectMilitantRequest(Militant $militant, Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }

        $request->validate([
            'motif_refus' => 'required|string|max:255',
        ]);

        $militant->status_verification = 'refuse';
        $militant->motif_refus = $request->motif_refus;
        $militant->save();

        return response()->json([
            'success' => true,
            'militant' => $militant
        ]);
    }

    public function listMilitantRequests(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Accès refusé. Seul un admin peut accéder à la liste des admins.'], 403);
        }
        $requests = Militant::filter($request->all())->where('status_verification', 'en cours')
            ->orWhere('status_verification', 'corrige')
            ->orWhere('status_verification', 'refuse')
            ->filter($request->all())
            ->get();

        return response()->json([
            'success' => true,
            'requests' => $requests
        ]);
    }

    public function paiementRequestMilitant(Request $request)
    {
        $user = $request->user();
        if (!$user || !($user->isAdmin() || $user->isAgent())) {
            return response()->json(['message' => 'Accès refusé.'], 403);
        }

        $request->validate([
            'admin_id' => 'required|integer|exists:users,id',
            'militant_id' => 'required|integer|exists:militants,id',
            'montant' => 'required|numeric|min:0',
            'type_paiement' => 'required|string|max:50',
            'reference' => 'required|string|unique:paiements,reference',
        ]);

        // Création du paiement lié à la demande militant
        $paiement = Paiement::create([
            'militant_id' => $request->militant_id,
            'montant' => $request->montant,
            'mode' => $request->mode,
            'effectue_par' => $user->id,
        ]);

        // Mise à jour du statut de paiement du militant
        $militant = \App\Models\Militant::find($request->militant_id);
        $militant->status_paiement = 'paid';
        $militant->save();

        return response()->json([
            'success' => true,
            'message' => 'Paiement pour la demande militant enregistré avec succès',
            'paiement' => $paiement,
            'militant' => $militant,
        ]);
    }
}
