<?php

namespace App\Http\Controllers\Api;

use App\Models\Militant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Militants extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function militantList()
    {
        $militants = Militant::all();
        return response()->json($militants);
    }


    /**
     * creating a new resource.
     */
    public function MilitantRequest(Request $request)
    {
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
            'status' => $request->status,
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
     * update resource in storage.
     */
    public function updateMilitant(Militant $militant, Request $request)
    {
        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:militants,email,' . $militant->id,
            'telephone' => 'sometimes|string|max:10',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'sometimes|string|max:255',
            'user_id' => 'sometimes|integer|exists:users,id',
            'circonscription_id' => 'sometimes|integer|exists:circonscriptions,id',
            'departement_id' => 'sometimes|integer|exists:departements,id',
            'commune_id' => 'sometimes|integer|exists:communes,id',
            'reference_carte' => 'sometimes|integer|max:255',
            'status_paiement' => 'sometimes|string|max:255',
            'removed' => 'nullable|string|max:10',
            'motif_refus' => 'nullable|string|max:255',
            'status_impression' => 'sometimes|string|max:255',
            'status_verification' => 'sometimes|string|max:255',
        ]);

        $militant->update($request->all());

        return response()->json([
            'success' => true,
            'militant' => $militant
        ]);
    }

    /**
     * accept a request.
     */
    public function acceptMilitantRequest(Militant $militant)
    {
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
    public function rejectMilitantRequest(Militant $militant, Request $request){
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
    
}
