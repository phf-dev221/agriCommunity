<?php

namespace Database\Seeders;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        // Définir toutes les permissions de base
        $resources = [
            'products',
            'customers',
            'invoices',
            'users',
            
        ];

        $actions = [
            'view',
            'create',
            'edit',
            'delete',
            'export'
           
        ];

        $permissions = [];
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                $permissions[] = "{$action}-{$resource}";
            }
        }

        // Créer les permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $gestionnaireRole = Role::firstOrCreate(['name' => 'agriculteur']);
        $vendeurRole = Role::firstOrCreate(['name' => 'acheteur']);

        // Donner toutes les permissions au super-admin
        $superAdminRole->givePermissionTo(Permission::all());

        // Donner les permissions adaptées à chaque rôle
        $adminRole->givePermissionTo([
            'view-products', 'create-products', 'edit-products', 'delete-products',
           
            
            'view-customers', 'create-customers', 'edit-customers', 'delete-customers',
            'view-invoices', 'create-invoices', 'edit-invoices', 'delete-invoices',
            'view-users', 'create-users', 'edit-users', 'delete-users'
            
        ]);

        $gestionnaireRole->givePermissionTo([
            'view-products', 'edit-products',

            'view-customers', 'create-customers', 'edit-customers',
            'view-invoices', 'create-invoices', 'edit-invoices',
            
        ]);

        $vendeurRole->givePermissionTo([
            'view-products',
           
            'view-customers', 'create-customers',
            'view-invoices', 'create-invoices',
        ]);

        // Créer un utilisateur SuperAdmin
        $superAdmin = User::firstOrCreate([
            'email' => 'superadmin@example.com',
            'name' => 'Super Admin',
            'phone' => '779009989',
            'password' => bcrypt('password'),
           
        ]);

        $superAdmin->assignRole('superadmin');
    }
}
