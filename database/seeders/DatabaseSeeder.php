<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    private $permissions = [
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
        'gestion_stock'
    ];


    public function run(): void
    {

        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }





        // Créer un administrateur directement après la création de la table
        $user = new User();
        $user->nom = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->role = "admin";
        $user->adresse = '123 rue de la paix';
        $user->phone = '0612345678';
        $user->zipcode = '75000';
        $user->password = Hash::make('123456789');
        $user->save();
        
        $permissions = Permission::pluck('id', 'id')->all();

        $role = Role::create(['name' => 'admin']);
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);


        $role = Role::create(['name' => 'personnel']);


    }
}
