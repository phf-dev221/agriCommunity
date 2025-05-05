<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    // SuperAdmin : Lister tous les rôles
    public function indexRoles(): JsonResponse
    {
        $roles = Role::all();
        return response()->json(['roles' => $roles], 200);
    }

    // SuperAdmin : Créer un rôle
    public function storeRole(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'guard_name' => 'required|string|in:api'
        ]);
        $role = Role::create($data);
        return response()->json(['message' => 'Role created', 'role' => $role], 201);
    }
    // SuperAdmin : Créer un rôle
    public function updateRole(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'name' => 'string|unique:roles,name',
            
        ]);
        $role = Role::findById($id);
        $roleUpdate = $role->update($data);
        return response()->json([
            'statut' =>'succes',
            'message' => 'Role modifié', 'role' => $roleUpdate
        ], 200);
    }
    // SuperAdmin : Supprimer un rôle
    public function destroyRole(string $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return response()->json([
            'statut' =>'succes',
            'message' => 'Role deleted'
        ], 200);
    }

    // SuperAdmin : Lister toutes les permissions
    public function indexPermissions(): JsonResponse
    {
        $permissions = Permission::all();
        return response()->json([
            'statut' =>'succes',
            'permissions' => $permissions
        ], 200);
    }

    // SuperAdmin : Créer une permission
    public function storePermission(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string|in:api'
        ]);
        $permission = Permission::create($data);
        return response()->json(['message' => 'Permission created', 'permission' => $permission], 201);
    }

        // SuperAdmin : Créer une permission
        public function updatePermission(Request $request, string $id): JsonResponse
        {
            $data = $request->validate([
                'name' => 'string|unique:permissions,name'
            ]);
            $permission = Permission::findById($id);
            $permissionUpdate = $permission->update($data);
            return response()->json(['message' => 'Permission modifé', 'permission' => $permissionUpdate], 200);
        }

    // SuperAdmin : Supprimer une permission
    public function destroyPermission(string $id): JsonResponse
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();
        return response()->json(['message' => 'Permission deleted'], 200);
    }

    // SuperAdmin : Assigner une permission à un rôle (global)
    public function assignPermission(Request $request): JsonResponse
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id'
        ]);
        $role = Role::findOrFail($data['role_id']);
        $permission = Permission::findOrFail($data['permission_id']);
        $role->givePermissionTo($permission);
        return response()->json([
            'statut' =>'succes',
            'message' => 'Permission assigned to role'
        ], 200);
    }

    // Tous : Vérifier une permission
    public function checkPermission(Request $request): JsonResponse
    {
       
        $data = $request->validate(['permission' => 'required|string']);
        $user = auth()->guard('api')->user();
         /** @var \App\Models\User $user */
        $hasPermission = $user->hasPermissionTo($data['permission']);
        return response()->json(['has_permission' => $hasPermission], 200);
    }

    // Admin : Lister les rôles disponibles
    public function tenantRoles(): JsonResponse
    {
        $roles = Role::where('name', '!=', 'superadmin')->get(); // Rôles globaux créés par SuperAdmin
        return response()->json(['roles' => $roles], 200);
    }

    

   
}