<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Add permissions based on your needs.
        $permissions = [
            'view.dashboard',
            'view.users',
            'edit.users',
            'create.users',
            'delete.users',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Add permissions to roles
        $role = Role::where('name', 'super_admin')->first();
        $role->givePermissionTo(Permission::all()->pluck('name'));

        $role = Role::where('name', 'admin')->first();
        $role->givePermissionTo(['view.dashboard', 'view.users']);
    }
}
