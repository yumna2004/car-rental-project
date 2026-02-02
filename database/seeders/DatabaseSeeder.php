<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        \App\Models\User::create([
            'name' => 'Admin AutoRental',
            'email' => 'admin@autorental.nl',
            'password' => 'password',
            'phone' => '06-12345678',
            'address' => 'Hoofdstraat 1, Amsterdam',
            'role' => 'admin',
        ]);

        // Test user
        \App\Models\User::create([
            'name' => 'Jan de Vries',
            'email' => 'jan@example.com',
            'password' => 'password',
            'phone' => '06-87654321',
            'address' => 'Testlaan 2, Rotterdam',
            'role' => 'user',
        ]);

        // Call VehicleSeeder
        $this->call(VehicleSeeder::class);
    }
}
