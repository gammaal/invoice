<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();

        // Customer 1 — 2 kendaraan
        Vehicle::create([
            'customer_id'  => $customers[0]->id,
            'plate_number' => 'B 1234 ABC',
            'brand'        => 'Toyota',
            'model'        => 'Avanza',
            'year'         => '2019',
            'color'        => 'Putih',
            'engine_number'=> 'K3VE-1234567',
            'chassis_number'=> 'MHFM1BA3JGK123456',
        ]);

        Vehicle::create([
            'customer_id'  => $customers[0]->id,
            'plate_number' => 'B 5678 DEF',
            'brand'        => 'Honda',
            'model'        => 'Brio',
            'year'         => '2021',
            'color'        => 'Merah',
        ]);

        // Customer 2
        Vehicle::create([
            'customer_id'  => $customers[1]->id,
            'plate_number' => 'D 4321 GHI',
            'brand'        => 'Suzuki',
            'model'        => 'Ertiga',
            'year'         => '2020',
            'color'        => 'Silver',
        ]);

        // Customer 3
        Vehicle::create([
            'customer_id'  => $customers[2]->id,
            'plate_number' => 'L 9999 JKL',
            'brand'        => 'Daihatsu',
            'model'        => 'Xenia',
            'year'         => '2018',
            'color'        => 'Hitam',
        ]);
    }
}
