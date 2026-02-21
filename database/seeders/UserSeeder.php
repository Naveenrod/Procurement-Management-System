<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin User', 'email' => 'admin@procurement.test', 'role' => 'admin'],
            ['name' => 'Manager User', 'email' => 'manager@procurement.test', 'role' => 'manager'],
            ['name' => 'Buyer User', 'email' => 'buyer@procurement.test', 'role' => 'buyer'],
            ['name' => 'Buyer Two', 'email' => 'buyer2@procurement.test', 'role' => 'buyer'],
            ['name' => 'Supplier One', 'email' => 'supplier@procurement.test', 'role' => 'supplier'],
            ['name' => 'Supplier Two', 'email' => 'supplier2@procurement.test', 'role' => 'supplier'],
            ['name' => 'Warehouse Worker', 'email' => 'warehouse@procurement.test', 'role' => 'warehouse_worker'],
            ['name' => 'Warehouse Two', 'email' => 'warehouse2@procurement.test', 'role' => 'warehouse_worker'],
        ];

        foreach ($users as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password'), 'email_verified_at' => now()]
            );
            $user->syncRoles([$data['role']]);
        }
    }
}
