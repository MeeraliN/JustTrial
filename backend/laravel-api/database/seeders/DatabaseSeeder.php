<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            MetadataSeeder::class,
        ]);

        $admin = User::query()->updateOrCreate([
            'email' => 'admin@rentdirect.test',
        ], [
            'account_type' => 'staff',
            'name' => 'Super Admin',
            'phone' => '9000000000',
            'locale' => 'en',
            'status' => 'active',
            'password' => Hash::make('Admin@12345'),
            'email_verified_at' => now(),
        ]);

        $admin->syncRoles(['super_admin']);
    }
}
