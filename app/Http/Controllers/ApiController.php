<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Tenant;
use App\Http\Requests\UserRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;

class ApiController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $data = $request->validated();

                // Vérifier le rôle
                $role = SpatieRole::findById($data['role_id']);
                if (!$role) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Rôle introuvable',
                    ], 404);
                }

                // Créer un tenant pour les agriculteurs (role_id = 2)
                $tenantId = null;
                if ($role->name === 'agriculteur') { // Plus robuste que role_id == 2
                    $tenant = Tenant::create([
                        'name' => $data['name'] . ' Farm', // Ajout d'un suffixe pour éviter les conflits
                       
                    ]);
                    $tenantId = $tenant->id;
                }

                // Créer l'utilisateur
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'tenant_id' => $tenantId, // null pour acheteur ou autres
                ]);

                // Assigner le rôle
                $user->assignRole($role);

                // Générer le token JWT
                $token = auth()->guard('api')->login($user);

                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'user' => $user,
                    'access_token' => $token,
                    'token_type' => 'bearer',
                   
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'inscription',
                'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
            ], 500);
        }
    }

    /**
     * Authenticate a user with the provided credentials.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $credentials)
    {

        $identifier = $credentials['identifiant'];
        $password = $credentials['password'];
    
        // Vérifier si l'identifiant est un email ou un numéro de téléphone
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (!$token = auth()->guard('api')->attempt([$field => $identifier, 'password' => $password])) {
            return response()->json([
                'success' => false,
                'message' => 'Identifiants invalides',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Log out the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ], 200);
    }

    /**
     * Return the profile of the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return response()->json([
            'success' => true,
            'user' => auth()->guard('api')->user(),
        ], 200);
    }

    /**
     * Update the profile of the currently authenticated user.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->json([
            'success' => true,
            'user' => $user,
            'message' => 'Profil mis à jour avec succès',
        ], 200);
    }

    /**
     * Refresh the authentication token of the currently authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshToken()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
        ], 200);
    }
}