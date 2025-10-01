<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Ensure the 'admin' role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create or fetch the admin user
        $user = User::firstOrCreate(
            ['email' => 'shan@gmail.com'],
            [
                'name' => 'Shan',
                'role' => 'house_owner',
                'password' => Hash::make('@Shan5640'), // change in production
            ]
        );

        // Assign Spatie role
        if (! $user->hasRole('admin')) {
            $user->assignRole($adminRole);
        }
    }
}
