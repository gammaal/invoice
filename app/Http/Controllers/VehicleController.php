<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with('customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $vehicles = $query->latest()->paginate(15);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create(Request $request)
    {
        $customers  = Customer::orderBy('name')->get();
        $customerId = $request->get('customer_id'); // pre-fill dari halaman customer
        return view('vehicles.create', compact('customers', 'customerId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id'    => ['required', 'exists:customers,id'],
            'plate_number'   => ['required', 'string', 'max:20'],
            'brand'          => ['required', 'string', 'max:50'],
            'model'          => ['required', 'string', 'max:50'],
            'year'           => ['nullable', 'digits:4', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'color'          => ['nullable', 'string', 'max:30'],
            'engine_number'  => ['nullable', 'string', 'max:50'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ], [
            'customer_id.required'  => 'Pemilik kendaraan wajib dipilih.',
            'plate_number.required' => 'Nomor plat wajib diisi.',
            'brand.required'        => 'Merek kendaraan wajib diisi.',
            'model.required'        => 'Model kendaraan wajib diisi.',
            'year.digits'           => 'Tahun harus 4 digit.',
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        $customers = Customer::orderBy('name')->get();
        return view('vehicles.edit', compact('vehicle', 'customers'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'customer_id'    => ['required', 'exists:customers,id'],
            'plate_number'   => ['required', 'string', 'max:20'],
            'brand'          => ['required', 'string', 'max:50'],
            'model'          => ['required', 'string', 'max:50'],
            'year'           => ['nullable', 'digits:4', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'color'          => ['nullable', 'string', 'max:30'],
            'engine_number'  => ['nullable', 'string', 'max:50'],
            'chassis_number' => ['nullable', 'string', 'max:50'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->invoices()->exists()) {
            return back()->with('error', 'Kendaraan tidak dapat dihapus karena memiliki riwayat servis.');
        }
        $vehicle->delete();
        return redirect()->route('vehicles.index')
            ->with('success', 'Kendaraan berhasil dihapus.');
    }

    /**
     * API endpoint: ambil kendaraan milik customer tertentu.
     * Digunakan oleh JavaScript di form invoice.
     */
    public function byCustomer(Customer $customer)
    {
        $vehicles = $customer->vehicles()
            ->select('id', 'plate_number', 'brand', 'model', 'year', 'color')
            ->get()
            ->map(fn($v) => [
                'id'    => $v->id,
                'label' => "{$v->brand} {$v->model} - {$v->plate_number}" . ($v->year ? " ({$v->year})" : ''),
            ]);

        return response()->json($vehicles);
    }
}
