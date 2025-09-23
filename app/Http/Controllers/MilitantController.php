<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Militant;
use App\Models\User;
use App\Models\Circonscription;
use App\Models\Departement;
use App\Models\Communes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
        Log::info($request);
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:militants,email',
                'telephone' => 'nullable|string|max:20',
                'adresse' => 'nullable|string|max:255',
                'profession' => 'nullable|string|max:255',
                'sexe' => 'nullable|string',
                'circonscription_id' => 'required|exists:circonscriptions,code_circ',
                'departement_id' => 'required|exists:departements,code_dep',
                'commune_id' => 'required|exists:communes,code_com',
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
            if ($request->filled('photo')) {
                $photoData = $request->photo;

                if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                    $photoData = substr($photoData, strpos($photoData, ',') + 1);
                    $type = strtolower($type[1]);

                    $photoData = base64_decode($photoData);
                    if ($photoData === false) {
                        return response()->json(['success' => false, 'message' => 'Image invalide'], 422);
                    }

                    $photoName = time() . '_' . Str::slug($request->nom . '_' . $request->prenom) . '.' . $type;

                    // 🔥 Sauvegarde directe dans public/militants
                    $path = public_path("storage/militants/{$photoName}");

                    // Crée le dossier s’il n’existe pas
                    if (!file_exists(dirname($path))) {
                        mkdir(dirname($path), 0777, true);
                    }

                    file_put_contents($path, $photoData);

                    $data['photo'] = "militants/{$photoName}";
                }
            }

            
            // Génération de la référence de carte unique
            $data['reference_carte'] = 'MIL-' . strtoupper(Str::random(8));
            
            $militant = Militant::create([
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'email' => $request->email,
                'telephone' => $request->telephone,
                'photo' => $data['photo'],
                'adresse' => $request->adresse,
                'profession' => $request->profession,
                'sexe' => $request->sexe,
                'circonscription_id' => $request->circonscription_id,
                'departement_id' => $request->departement_id,
                'commune_id' => $request->commune_id,
                'reference_carte' => $data['reference_carte'],
                'user_id'=>Auth::user()->id
            ]);
            // $militant->load(['user', 'circonscription', 'departement', 'commune']);
            
            return response()->json([
                'success' => true,
                'message' => 'Militant créé avec succès'
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
                'adresse' => 'nullable|string|max:255',
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

    public function getStatistique(Request $request) {
        $user = Auth::user();

        $militant_femme = Militant::where('user_id',$user->id)
                                    ->where('sexe', 'Femme')
                                    ->get();
        $militant_homme = Militant::where('user_id',$user->id)
                            ->where('sexe', 'Homme')
                            ->get();
        $militant_impaye = Militant::where('user_id',$user->id)
                                    ->where('status_paiement', 'unpaid')
                                    ->get();
        $militant_paye = Militant::where('user_id',$user->id)
                                    ->where('status_paiement', 'paid')
                                    ->get();
        $militant_non_imprime = Militant::where('user_id',$user->id)
                                    ->where('status_impression', 'not_printed')
                                    ->get();
        $militant_imprime = Militant::where('user_id',$user->id)
                                    ->where('status_impression', 'printed')
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
            ->where('user_id', $user->id)
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
            ->where('user_id', $user->id)
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
}
