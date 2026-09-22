<?php

namespace Database\Seeders;

use App\Models\Mechanic;
use Illuminate\Database\Seeder;

class MechanicSeeder extends Seeder
{
    public function run(): void
    {
        $mechanics = [
            ['name' => 'Budi Santoso',   'phone' => '081234567001', 'specialization' => 'Mesin & Transmisi', 'is_active' => true],
            ['name' => 'Agus Widodo',    'phone' => '081234567002', 'specialization' => 'Kelistrikan',        'is_active' => true],
            ['name' => 'Hendra Gunawan', 'phone' => '081234567003', 'specialization' => 'AC & Pendingin',     'is_active' => true],
            ['name' => 'Rudi Hartono',   'phone' => '081234567004', 'specialization' => 'Body & Cat',         'is_active' => true],
        ];

        foreach ($mechanics as $m) {
            Mechanic::create($m);
        }
    }
}
