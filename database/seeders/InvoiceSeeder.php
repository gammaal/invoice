<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Mechanic;
use App\Models\Product;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $vehicles  = Vehicle::all();
        $mechanics = Mechanic::all();
        $products  = Product::all()->keyBy('name');

        // Invoice 1 — Ganti Oli + Filter (LUNAS)
        $this->make(
            customer: $customers[0], vehicle: $vehicles[0], mechanic: $mechanics[0],
            status: 'paid', invoiceDate: '2026-09-01', dueDate: '2026-09-01',
            mileageIn: 45000, mileageOut: 45010,
            complaint: 'Ganti oli rutin',
            diagnosis: 'Oli mesin sudah kotor, filter oli perlu diganti',
            items: [
                ['product' => $products['Ganti Oli Mesin'],        'qty' => 1],
                ['product' => $products['Oli Mesin Shell Helix 10W-40'], 'qty' => 4],
                ['product' => $products['Filter Oli'],             'qty' => 1],
            ],
            discount: 0, tax: 0
        );

        // Invoice 2 — Tune Up + AC (SEDANG DIKERJAKAN)
        $this->make(
            customer: $customers[1], vehicle: $vehicles[2], mechanic: $mechanics[1],
            status: 'in_progress', invoiceDate: '2026-09-20', dueDate: '2026-09-20',
            mileageIn: 72000, mileageOut: null,
            complaint: 'Mesin terasa kurang bertenaga, AC tidak dingin',
            diagnosis: 'Throttle body kotor, freon habis',
            items: [
                ['product' => $products['Tune Up Ringan'],  'qty' => 1],
                ['product' => $products['Servis AC'],       'qty' => 1],
                ['product' => $products['Freon R134a'],     'qty' => 1],
                ['product' => $products['Busi NGK'],        'qty' => 4],
            ],
            discount: 25000, tax: 0
        );

        // Invoice 3 — Rem + Spooring (BELUM DIBAYAR)
        $this->make(
            customer: $customers[2], vehicle: $vehicles[3], mechanic: $mechanics[2],
            status: 'unpaid', invoiceDate: '2026-09-18', dueDate: '2026-09-25',
            mileageIn: 38500, mileageOut: 38510,
            complaint: 'Bunyi saat ngerem, setir goyang di kecepatan tinggi',
            diagnosis: 'Kampas rem depan tipis, perlu spooring balancing',
            items: [
                ['product' => $products['Ganti Kampas Rem Depan'], 'qty' => 1],
                ['product' => $products['Kampas Rem Depan'],       'qty' => 1],
                ['product' => $products['Spooring & Balancing'],   'qty' => 1],
            ],
            discount: 0, tax: 0
        );

        // Invoice 4 — Cuci + Filter Udara (LUNAS)
        $this->make(
            customer: $customers[0], vehicle: $vehicles[1], mechanic: $mechanics[3],
            status: 'paid', invoiceDate: '2026-09-19', dueDate: '2026-09-19',
            mileageIn: 28000, mileageOut: 28000,
            complaint: 'Cuci mobil & servis ringan',
            diagnosis: 'Filter udara kotor',
            items: [
                ['product' => $products['Cuci Mobil'],    'qty' => 1],
                ['product' => $products['Filter Udara'],  'qty' => 1],
            ],
            discount: 10000, tax: 0
        );

        // Invoice 5 — Aki (MENUNGGU SPAREPART)
        $this->make(
            customer: $customers[1], vehicle: $vehicles[2], mechanic: $mechanics[0],
            status: 'waiting', invoiceDate: '2026-09-21', dueDate: '2026-09-23',
            mileageIn: 72500, mileageOut: null,
            complaint: 'Aki sering tekor, susah starter',
            diagnosis: 'Aki sudah lemah, perlu diganti',
            items: [
                ['product' => $products['Aki Kering GS Astra'], 'qty' => 1],
            ],
            discount: 0, tax: 0
        );
    }

    private function make(
        $customer, $vehicle, $mechanic,
        string $status, string $invoiceDate, string $dueDate,
        ?int $mileageIn, ?int $mileageOut,
        string $complaint, string $diagnosis,
        array $items,
        float $discount, float $tax
    ): void {
        $subtotal = 0;
        $details  = [];

        foreach ($items as $item) {
            $line      = $item['product']->price * $item['qty'];
            $subtotal += $line;
            $details[] = [
                'product_id' => $item['product']->id,
                'quantity'   => $item['qty'],
                'price'      => $item['product']->price,
                'subtotal'   => $line,
            ];
        }

        $invoice = Invoice::create([
            'invoice_number'    => Invoice::generateNumber(),
            'customer_id'       => $customer->id,
            'vehicle_id'        => $vehicle->id,
            'mechanic_id'       => $mechanic->id,
            'invoice_date'      => $invoiceDate,
            'due_date'          => $dueDate,
            'mileage_in'        => $mileageIn,
            'mileage_out'       => $mileageOut,
            'complaint'         => $complaint,
            'diagnosis'         => $diagnosis,
            'vehicle_condition' => 'Kondisi umum baik',
            'subtotal'          => $subtotal,
            'discount'          => $discount,
            'tax'               => $tax,
            'total'             => $subtotal - $discount + $tax,
            'status'            => $status,
        ]);

        foreach ($details as $d) {
            InvoiceDetail::create(array_merge($d, ['invoice_id' => $invoice->id]));
        }
    }
}
