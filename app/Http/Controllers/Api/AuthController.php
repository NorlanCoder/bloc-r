<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'telephone' => 'required|string|max:20',
            // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'password' => bcrypt($request->password),
            // 'photo' => $request->photo,
        ]);

        // if (empty($request->photo)) {
        //     $path = $request->file('photo')->store('photos', 'public');
        //     $user->photo = $path;
        //     $user->save();
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        // verifier si l'utilisateur est actif
        if (!$user->is_active) {
            return response()->json(['message' => 'Account is not active', 'code' => 'nonoctive'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier le mot de passe actuel si changement de mot de passe
        // if ($request->has('password')) {
        //     if (!Hash::check($request->current_password, $user->password)) {
        //         return response()->json(['message' => 'Mot de passe actuel incorrect'], 422);
        //     }
        //     $user->password = Hash::make($request->password);
        // }

        $user->fill($request->only(['nom', 'prenom', 'telephone']));
        $user->save();

        return response()->json([
            'message' => 'Profil modifié avec succès',
            'user' => $user,
            'success' => true,
            'status' => 200
        ],200);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
