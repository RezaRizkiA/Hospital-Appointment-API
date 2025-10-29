<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Nette\Utils\Random;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['manager', 'customer'];
        $permissions = ['create role', 'edit role', 'delete role', 'view role'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $managerRole = Role::where('name', 'manager')->first();
        $managerRole->givePermissionTo($permissions);

        foreach ($roles as $roleName) {
            $user = User::factory()->create([
                'name' => ucfirst($roleName) . ' User',
                'email' => $roleName . '@example.com',
                'phone' => fake()->phoneNumber(),
                'photo' => fake()->imageUrl(200, 200, 'people', true, 'profile'),
                'password' => Hash::make('password123'),
                'gender' => fake()->randomElement(['Male', 'Female'])
            ]);
            $user->assignRole($roleName);
        }
    }
}
