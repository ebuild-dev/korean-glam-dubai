<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'dashboard',
            'clients_view',
            'clients_delete',
            'product_view',
            'product_add',
            'product_edit',
            'product_delete',
            'order_view',
            'order_add',
            'order_edit',
            'order_delete',
            'setting_view',
            'gestion_stock',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer un administrateur directement après la création de la table
        $user = new User();
        $user->nom = 'Ameni';
        $user->email = 'Amenib8@outlook.com';
        $user->role = "admin";
        $user->adresse = '123 rue de la paix';
        $user->phone = '0612345678';
        $user->zipcode = '75000';
        $user->password = Hash::make('123456789');
        $role = Role::firstOrCreate(['name' => 'admin']);
        $role->syncPermissions(Permission::all());

        $user->assignRole($role);
        $user->save();

    }
}
