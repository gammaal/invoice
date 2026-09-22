<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name'    => 'PT Maju Bersama',
                'email'   => 'info@majubersama.com',
                'phone'   => '021-5551234',
                'address' => 'Jl. Sudirman No. 10, Jakarta Pusat',
            ],
            [
                'name'    => 'CV Sumber Rejeki',
                'email'   => 'cs@sumberrejeki.com',
                'phone'   => '022-7778888',
                'address' => 'Jl. Asia Afrika No. 5, Bandung',
            ],
            [
                'name'    => 'Toko Serba Ada',
                'email'   => 'toko@serba-ada.com',
                'phone'   => '031-4445566',
                'address' => 'Jl. Pemuda No. 22, Surabaya',
            ],
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }
    }
}
