<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CustomerSeeder::class,
            MechanicSeeder::class,
            VehicleSeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
