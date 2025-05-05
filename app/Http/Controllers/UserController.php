<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role as SpatieRole;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{  
        $user = auth()->guard('api')->user();
        /** @var \App\Models\User $user */
        if($user->hasRole('superadmin')){
            $user = User::all();
        }elseif($user->hasRole('agriculteur')){
            $users = User::where('tenant_id', $user->tenant_id);
        }

        if($users->isEmpty()){
            return response()->json([
                'status' => 'success',
                'message' => 'Aucun utilisateurs pour l\'instant',
                'users'=>$users
            ], 200);
        }
        return response()->json([
            'status' => 'success',
            'users' => $users
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur lors de la recuperation des utilisateurs',
            'error' => config('app.debug') ? $e->getMessage() : 'Veuillez réessayer plus tard',
        ], 500);
    }
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
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

                
                $tenant = auth()->guard('api')->user();

                // Créer l'utilisateur
                $user = User::create([
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role_id' => $data['role_id'],
                    'tenant_id' => $tenant->tenant_id, 
                ]);

                // Assigner le rôle
                $user->assignRole($role);
               

                return response()->json([
                    'success' => true,
                    'message' => 'Utilisateur créé avec succès',
                    'user' => $user,
                  
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $user = auth()->guard('api')->user();
/** @var \App\Models\User $user */
        if(!$user->hasRoles('superadmin') && $id == 1){
            return response()->json([
                'status' => false,
                'message' => 'vous n\'avait pas acces a cette resource'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
