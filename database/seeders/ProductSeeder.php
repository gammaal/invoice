<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // === JASA ===
        $services = [
            ['name' => 'Ganti Oli Mesin',        'description' => 'Jasa penggantian oli mesin termasuk filter oli',         'price' => 50000,  'stock' => 0,   'unit' => 'kali'],
            ['name' => 'Tune Up Ringan',          'description' => 'Pembersihan throttle body, injektor, busi',              'price' => 150000, 'stock' => 0,   'unit' => 'kali'],
            ['name' => 'Servis AC',               'description' => 'Pengecekan dan isi freon AC',                            'price' => 200000, 'stock' => 0,   'unit' => 'kali'],
            ['name' => 'Spooring & Balancing',    'description' => 'Penyetelan sudut roda dan balancing ban',                'price' => 175000, 'stock' => 0,   'unit' => 'kali'],
            ['name' => 'Ganti Kampas Rem Depan',  'description' => 'Jasa penggantian kampas rem cakram depan',              'price' => 75000,  'stock' => 0,   'unit' => 'kali'],
            ['name' => 'Cuci Mobil',              'description' => 'Cuci mobil standard luar dalam',                        'price' => 35000,  'stock' => 0,   'unit' => 'kali'],
        ];

        foreach ($services as $s) {
            Product::create(array_merge($s, ['type' => 'service']));
        }

        // === SPARE PART ===
        $parts = [
            ['name' => 'Oli Mesin Shell Helix 10W-40', 'description' => 'Oli mesin mineral 1 liter',        'price' => 55000,  'stock' => 100, 'unit' => 'liter'],
            ['name' => 'Filter Oli',                   'description' => 'Filter oli original',              'price' => 35000,  'stock' => 50,  'unit' => 'pcs'],
            ['name' => 'Filter Udara',                 'description' => 'Filter udara universal',           'price' => 65000,  'stock' => 30,  'unit' => 'pcs'],
            ['name' => 'Busi NGK',                     'description' => 'Busi standar NGK G-Power',         'price' => 25000,  'stock' => 80,  'unit' => 'pcs'],
            ['name' => 'Kampas Rem Depan',             'description' => 'Kampas rem cakram depan sepasang', 'price' => 185000, 'stock' => 20,  'unit' => 'set'],
            ['name' => 'Freon R134a',                  'description' => 'Freon AC R134a 1 kg',              'price' => 120000, 'stock' => 40,  'unit' => 'kg'],
            ['name' => 'Aki Kering GS Astra',          'description' => 'Aki kering 45Ah',                 'price' => 850000, 'stock' => 10,  'unit' => 'pcs'],
            ['name' => 'Wiper Blade',                  'description' => 'Wiper blade depan universal',     'price' => 75000,  'stock' => 25,  'unit' => 'pcs'],
        ];

        foreach ($parts as $p) {
            Product::create(array_merge($p, ['type' => 'part']));
        }
    }
}
