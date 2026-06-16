<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed default admin user
        User::updateOrCreate(
            ['email' => 'rayditarifan@gmail.com'],
            [
                'name' => 'Radhitya Admin',
                'password' => Hash::make('Pristine123!'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            BarangSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
