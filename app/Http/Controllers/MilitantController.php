<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Militant;
use App\Models\User;
use App\Models\Circonscription;
use App\Models\Departement;
use App\Models\Communes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class MilitantController extends Controller
{
    /**
     * Afficher la liste des militants
     */
    public function index(Request $request)
    {
        try {
            $query = Militant::with(['user', 'circonscription', 'departement', 'commune']);
            
            // Filtres optionnels
            if ($request->has('status')) {
                $query->where('status', $request->status);
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
            
            $militants = $query->orderBy('created_at', 'desc')->paginate(15);
            
            return response()->json([
                'success' => true,
                'data' => $militants
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des militants',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher les militants d'un agent spécifique
     */
    public function getByAgent()
    {
        $agent_id = Auth::user()->id;
        try {
            $militants = Militant::with(['user', 'circonscription', 'departement', 'commune'])
                ->where('user_id', $agent_id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $militants
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des militants de l\'agent',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Afficher un militant spécifique
     */
    public function show($id)
    {
        try {
            $militant = Militant::with(['user', 'circonscription', 'departement', 'commune'])->find($id);
            
            if (!$militant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Militant non trouvé'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $militant
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération du militant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ajouter un nouveau militant
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:militants,email',
                'telephone' => 'nullable|string|max:20',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'addresse' => 'nullable|string|max:255',
                'profession' => 'nullable|string|max:255',
                'sexe' => 'nullable|string|in:masculin,feminin',
                'circonscription_id' => 'required|exists:circonscriptions,id',
                'departement_id' => 'required|exists:departements,id',
                'commune_id' => 'required|exists:communes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Gestion de l'upload de photo
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = time() . '_' . Str::slug($data['nom'] . '_' . $data['prenom']) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/militants', $photoName);
                $data['photo'] = 'militants/' . $photoName;
            }
            
            // Génération de la référence de carte unique
            $data['reference_carte'] = 'MIL-' . strtoupper(Str::random(8));
            
            $militant = Militant::create($data);
            $militant->load(['user', 'circonscription', 'departement', 'commune']);
            
            return response()->json([
                'success' => true,
                'message' => 'Militant créé avec succès',
                'data' => $militant
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du militant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Modifier un militant
     */
    public function update(Request $request, $id)
    {
        try {
            $militant = Militant::find($id);
            
            if (!$militant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Militant non trouvé'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nom' => 'sometimes|required|string|max:255',
                'prenom' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:militants,email,' . $id,
                'telephone' => 'nullable|string|max:20',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'addresse' => 'nullable|string|max:255',
                'profession' => 'nullable|string|max:255',
                'sexe' => 'nullable|string|in:masculin,feminin',
                'circonscription_id' => 'sometimes|exists:circonscriptions,id',
                'departement_id' => 'sometimes|exists:departements,id',
                'commune_id' => 'sometimes|exists:communes,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $request->all();
            
            // Gestion de l'upload de nouvelle photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($militant->photo && Storage::exists('public/' . $militant->photo)) {
                    Storage::delete('public/' . $militant->photo);
                }
                
                $photo = $request->file('photo');
                $photoName = time() . '_' . Str::slug($data['nom'] . '_' . $data['prenom']) . '.' . $photo->getClientOriginalExtension();
                $photo->storeAs('public/militants', $photoName);
                $data['photo'] = 'militants/' . $photoName;
            }
            
            $militant->update($data);
            $militant->load(['user', 'circonscription', 'departement', 'commune']);
            
            return response()->json([
                'success' => true,
                'message' => 'Militant modifié avec succès',
                'data' => $militant
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la modification du militant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un militant
     */
    public function destroy($id)
    {
        try {
            $militant = Militant::find($id);
            
            if (!$militant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Militant non trouvé'
                ], 404);
            }
            
            // Supprimer la photo si elle existe
            if ($militant->photo && Storage::exists('public/' . $militant->photo)) {
                Storage::delete('public/' . $militant->photo);
            }
            
            $militant->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Militant supprimé avec succès'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du militant',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Accepter une demande de militant
     */
    public function accept($id)
    {
        try {
            $militant = Militant::find($id);
            
            if (!$militant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Militant non trouvé'
                ], 404);
            }
            
            $militant->update([
                'status_verification' => 'correct',
                'status' => 'active'
            ]);
            
            $militant->load(['user', 'circonscription', 'departement', 'commune']);
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de militant acceptée',
                'data' => $militant
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'acceptation de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeter une demande de militant
     */
    public function reject(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'motif_refus' => 'required|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Le motif de refus est requis',
                    'errors' => $validator->errors()
                ], 422);
            }

            $militant = Militant::find($id);
            
            if (!$militant) {
                return response()->json([
                    'success' => false,
                    'message' => 'Militant non trouvé'
                ], 404);
            }
            
            $militant->update([
                'status_verification' => 'refuse',
                'status' => 'inactive',
                'motif_refus' => $request->motif_refus
            ]);
            
            $militant->load(['user', 'circonscription', 'departement', 'commune']);
            
            return response()->json([
                'success' => true,
                'message' => 'Demande de militant rejetée',
                'data' => $militant
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du rejet de la demande',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getDepartements(Request $request)
    {
        $departements = Departement::all();
        return response()->json([
            'success' => true,
            'data' => $departements
        ], 200);
    }

    public function getCommunes(Request $request)
    {
        $communes = Communes::all();
        return response()->json([
            'success' => true,
            'data' => $communes
        ], 200);
    }

    public function getCirconscriptions(Request $request)
    {
        $circonscriptions = Circonscription::all();
        return response()->json([
            'success' => true,
            'data' => $circonscriptions
        ], 200);
    }
}
