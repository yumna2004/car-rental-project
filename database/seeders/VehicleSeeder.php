<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = [
            [
                'title' => 'Toyota Yaris 2023',
                'description' => 'Zuinige en betrouwbare stadsauto. Perfect voor dagelijks gebruik.',
                'category' => 'personenauto',
                'price_per_day' => 45.00,
                'region' => 'Amsterdam',
                'transmission' => 'automaat',
            ],
            [
                'title' => 'Volkswagen Golf 2024',
                'description' => 'Moderne gezinsauto met ruime bagageruimte.',
                'category' => 'personenauto',
                'price_per_day' => 65.00,
                'region' => 'Rotterdam',
                'transmission' => 'schakel',
            ],
            [
                'title' => 'Mercedes E-Klasse 2024',
                'description' => 'Luxe zakenauto met lederen interieur.',
                'category' => 'personenauto',
                'price_per_day' => 120.00,
                'region' => 'Utrecht',
                'transmission' => 'automaat',
            ],
            [
                'title' => 'Ford Transit Verhuisbus',
                'description' => 'Ruime verhuisbus voor grote transporten.',
                'category' => 'verhuisbus',
                'price_per_day' => 85.00,
                'region' => 'Amsterdam',
                'transmission' => 'schakel',
            ],
            [
                'title' => 'Opel Vivaro Bestelbus',
                'description' => 'Praktische bestelbus voor zakelijk gebruik.',
                'category' => 'bestelbus',
                'price_per_day' => 70.00,
                'region' => 'Rotterdam',
                'transmission' => 'schakel',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::create($vehicle);
        }
    }
}
