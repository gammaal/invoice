<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Mechanic;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with(['customer', 'vehicle', 'mechanic']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('vehicle',  fn($v) => $v->where('plate_number', 'like', "%{$search}%"));
            });
        }

        $invoices = $query->latest()->paginate(15);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::with('vehicles')->orderBy('name')->get();
        $mechanics = Mechanic::where('is_active', true)->orderBy('name')->get();
        $products  = Product::orderBy('type')->orderBy('name')->get();
        return view('invoices.create', compact('customers', 'mechanics', 'products'));
    }

    public function store(InvoiceRequest $request)
    {
        DB::beginTransaction();
        try {
            $subtotal = collect($request->products)
                ->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

            $discount = (float) $request->input('discount', 0);
            $tax      = (float) $request->input('tax', 0);
            $total    = $subtotal - $discount + $tax;

            $invoice = Invoice::create([
                'invoice_number'   => Invoice::generateNumber(),
                'customer_id'      => $request->customer_id,
                'vehicle_id'       => $request->vehicle_id,
                'mechanic_id'      => $request->mechanic_id,
                'invoice_date'     => $request->invoice_date,
                'due_date'         => $request->due_date,
                'mileage_in'       => $request->mileage_in,
                'mileage_out'      => $request->mileage_out,
                'complaint'        => $request->complaint,
                'diagnosis'        => $request->diagnosis,
                'vehicle_condition'=> $request->vehicle_condition,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'tax'              => $tax,
                'total'            => $total,
                'status'           => $request->status,
            ]);

            foreach ($request->products as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat invoice: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'vehicle', 'mechanic', 'details.product']);
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('details.product');
        $customers = Customer::with('vehicles')->orderBy('name')->get();
        $mechanics = Mechanic::where('is_active', true)->orderBy('name')->get();
        $products  = Product::orderBy('type')->orderBy('name')->get();
        return view('invoices.edit', compact('invoice', 'customers', 'mechanics', 'products'));
    }

    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        DB::beginTransaction();
        try {
            $subtotal = collect($request->products)
                ->sum(fn($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 0));

            $discount = (float) $request->input('discount', 0);
            $tax      = (float) $request->input('tax', 0);
            $total    = $subtotal - $discount + $tax;

            $invoice->update([
                'customer_id'      => $request->customer_id,
                'vehicle_id'       => $request->vehicle_id,
                'mechanic_id'      => $request->mechanic_id,
                'invoice_date'     => $request->invoice_date,
                'due_date'         => $request->due_date,
                'mileage_in'       => $request->mileage_in,
                'mileage_out'      => $request->mileage_out,
                'complaint'        => $request->complaint,
                'diagnosis'        => $request->diagnosis,
                'vehicle_condition'=> $request->vehicle_condition,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'tax'              => $tax,
                'total'            => $total,
                'status'           => $request->status,
            ]);

            $invoice->details()->delete();
            foreach ($request->products as $item) {
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'Invoice berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui invoice.')->withInput();
        }
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function print(Invoice $invoice)
    {
        $invoice->load(['customer', 'vehicle', 'mechanic', 'details.product']);
        return view('invoices.print', compact('invoice'));
    }
}
