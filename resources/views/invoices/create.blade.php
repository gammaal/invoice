@extends('layouts.app')
@section('title','Buat Work Order')
@section('page-title','Buat Work Order Baru')

@section('content')
<form action="{{ route('invoices.store') }}" method="POST" id="invoice-form">
@csrf

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- ===== KOLOM KIRI ===== --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Info Kendaraan & Customer --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Informasi Kendaraan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Customer --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                    <select name="customer_id" id="customer-select"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                            {{ $errors->has('customer_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        onchange="loadVehicles(this.value)">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ old('customer_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Kendaraan --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kendaraan <span class="text-red-500">*</span></label>
                    <select name="vehicle_id" id="vehicle-select"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                            {{ $errors->has('vehicle_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">-- Pilih Customer Dulu --</option>
                    </select>
                    @error('vehicle_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Mekanik --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mekanik</label>
                    <select name="mechanic_id"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Mekanik --</option>
                        @foreach($mechanics as $m)
                            <option value="{{ $m->id }}" {{ old('mechanic_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->name }} @if($m->specialization)({{ $m->specialization }})@endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- KM Masuk --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">KM Masuk</label>
                    <input type="number" name="mileage_in" value="{{ old('mileage_in') }}"
                        placeholder="Contoh: 45000" min="0"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Tanggal Invoice --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Masuk <span class="text-red-500">*</span></label>
                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                            {{ $errors->has('invoice_date') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('invoice_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Due Date --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimasi Selesai <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}"
                        class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                            {{ $errors->has('due_date') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('due_date')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                {{-- Status --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                    <select name="status"
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['draft'=>'Draft','in_progress'=>'Sedang Dikerjakan','waiting'=>'Menunggu Sparepart','done'=>'Selesai','unpaid'=>'Belum Dibayar','paid'=>'Lunas','cancelled'=>'Dibatalkan'] as $val=>$lbl)
                            <option value="{{ $val }}" {{ old('status','in_progress')===$val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Keluhan & Diagnosa --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Keluhan & Diagnosa</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan Pelanggan</label>
                    <textarea name="complaint" rows="2"
                        placeholder="Contoh: Mesin bunyi kasar, AC tidak dingin..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('complaint') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa Mekanik</label>
                    <textarea name="diagnosis" rows="2"
                        placeholder="Hasil diagnosa mekanik..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('diagnosis') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kondisi Kendaraan Saat Masuk</label>
                    <textarea name="vehicle_condition" rows="2"
                        placeholder="Contoh: Goresan di bemper depan, kaca spion kiri pecah..."
                        class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('vehicle_condition') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Tabel Item --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Jasa & Spare Part</h3>
                <button type="button" id="btn-add-row"
                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Item
                </button>
            </div>

            @error('products')
                <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg text-xs text-red-600">{{ $message }}</div>
            @enderror

            <div class="overflow-x-auto -mx-5 px-5">
                <table class="w-full text-sm min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-5/12">Item</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase w-20">Qty</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Harga</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                            <th class="px-3 py-2 w-8"></th>
                        </tr>
                    </thead>
                    <tbody id="product-rows"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== KOLOM KANAN ===== --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">Ringkasan Biaya</h3>
            <div class="space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span class="font-medium text-gray-900" id="display-subtotal">Rp 0</span>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Diskon (Rp)</label>
                    <input type="number" name="discount" id="discount" value="{{ old('discount',0) }}"
                        min="0" step="500"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Pajak / PPN (Rp)</label>
                    <input type="number" name="tax" id="tax" value="{{ old('tax',0) }}"
                        min="0" step="500"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <hr class="border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-base font-bold text-gray-900">Total</span>
                    <span class="text-xl font-bold text-blue-600" id="display-total">Rp 0</span>
                </div>
                <input type="hidden" name="subtotal" id="subtotal-hidden" value="0">
                <input type="hidden" name="total" id="total-hidden" value="0">
            </div>
        </div>

        <div class="space-y-2">
            <button type="submit"
                class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors">
                Simpan Work Order
            </button>
            <a href="{{ route('invoices.index') }}"
               class="block w-full py-2.5 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </div>
</div>
</form>

<script>
// Data produk untuk dropdown
const PRODUCTS = {!! json_encode($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'type' => $p->type, 'price' => (float)$p->price, 'unit' => $p->unit])) !!};

// Load kendaraan saat customer dipilih
function loadVehicles(customerId) {
    const sel = document.getElementById('vehicle-select');
    sel.innerHTML = '<option value="">Memuat...</option>';
    if (!customerId) { sel.innerHTML = '<option value="">-- Pilih Customer Dulu --</option>'; return; }

    fetch(`/vehicles/by-customer/${customerId}`)
        .then(r => r.json())
        .then(vehicles => {
            sel.innerHTML = '<option value="">-- Pilih Kendaraan --</option>';
            vehicles.forEach(v => {
                const opt = document.createElement('option');
                opt.value = v.id;
                opt.textContent = v.label;
                @if(old('vehicle_id'))
                if (v.id == {{ old('vehicle_id',0) }}) opt.selected = true;
                @endif
                sel.appendChild(opt);
            });
        });
}

// Buat opsi dropdown produk
function buildOptions(selectedId) {
    let html = '<option value="">-- Pilih Item --</option>';
    const services = PRODUCTS.filter(p => p.type === 'service');
    const parts    = PRODUCTS.filter(p => p.type === 'part');
    if (services.length) {
        html += '<optgroup label="🔧 Jasa">';
        services.forEach(p => html += `<option value="${p.id}" data-price="${p.price}" data-unit="${p.unit}" ${p.id==selectedId?'selected':''}>${p.name}</option>`);
        html += '</optgroup>';
    }
    if (parts.length) {
        html += '<optgroup label="🔩 Spare Part">';
        parts.forEach(p => html += `<option value="${p.id}" data-price="${p.price}" data-unit="${p.unit}" ${p.id==selectedId?'selected':''}>${p.name}</option>`);
        html += '</optgroup>';
    }
    return html;
}

function createRow(index, productId='', qty=1, price=0) {
    return `
    <tr class="product-row border-t border-gray-100" data-index="${index}">
        <td class="px-3 py-2">
            <select name="products[${index}][product_id]"
                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 product-select"
                onchange="onProductChange(this)">
                ${buildOptions(productId)}
            </select>
        </td>
        <td class="px-3 py-2">
            <div class="flex items-center gap-1">
                <input type="number" name="products[${index}][quantity]" value="${qty}" min="0.1" step="0.5"
                    class="w-16 px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 qty-input"
                    oninput="recalcRow(this.closest('tr'))">
                <span class="text-xs text-gray-400 unit-label whitespace-nowrap"></span>
            </div>
        </td>
        <td class="px-3 py-2">
            <input type="number" name="products[${index}][price]" value="${price}" min="0" step="500"
                class="w-28 px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 price-input"
                oninput="recalcRow(this.closest('tr'))">
        </td>
        <td class="px-3 py-2 text-xs font-semibold text-gray-900 subtotal-display whitespace-nowrap">Rp 0</td>
        <td class="px-3 py-2">
            <button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </td>
    </tr>`;
}

let rowIndex = 0;
function addRow(productId='', qty=1, price=0) {
    document.getElementById('product-rows').insertAdjacentHTML('beforeend', createRow(rowIndex, productId, qty, price));
    const row = document.querySelector(`[data-index="${rowIndex}"]`);
    rowIndex++;
    // Set unit label jika ada product
    if (productId) {
        const p = PRODUCTS.find(x => x.id == productId);
        if (p) row.querySelector('.unit-label').textContent = p.unit;
    }
    recalcRow(row);
}

function removeRow(btn) {
    if (document.querySelectorAll('.product-row').length <= 1) { alert('Minimal satu item!'); return; }
    btn.closest('tr').remove();
    recalcAll();
}

function onProductChange(select) {
    const opt  = select.options[select.selectedIndex];
    const row  = select.closest('tr');
    row.querySelector('.price-input').value        = opt.dataset.price || 0;
    row.querySelector('.unit-label').textContent   = opt.dataset.unit  || '';
    recalcRow(row);
}

function recalcRow(row) {
    const qty   = parseFloat(row.querySelector('.qty-input').value)   || 0;
    const price = parseFloat(row.querySelector('.price-input').value) || 0;
    row.querySelector('.subtotal-display').textContent = 'Rp ' + (qty*price).toLocaleString('id-ID');
    recalcAll();
}

function recalcAll() {
    let sub = 0;
    document.querySelectorAll('.product-row').forEach(r => {
        sub += (parseFloat(r.querySelector('.qty-input').value)||0) * (parseFloat(r.querySelector('.price-input').value)||0);
    });
    const disc  = parseFloat(document.getElementById('discount').value)||0;
    const tax   = parseFloat(document.getElementById('tax').value)||0;
    const total = sub - disc + tax;
    document.getElementById('display-subtotal').textContent = 'Rp ' + sub.toLocaleString('id-ID');
    document.getElementById('display-total').textContent    = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('subtotal-hidden').value        = sub;
    document.getElementById('total-hidden').value           = total;
}

document.getElementById('btn-add-row').addEventListener('click', () => addRow());
document.getElementById('discount').addEventListener('input', recalcAll);
document.getElementById('tax').addEventListener('input', recalcAll);

// Restore old input atau default 1 baris
@if(old('products'))
    @foreach(old('products',[]) as $i => $item)
        addRow({{ $item['product_id']??0 }}, {{ $item['quantity']??1 }}, {{ $item['price']??0 }});
    @endforeach
@else
    addRow();
@endif

// Restore customer & vehicle
@if(old('customer_id'))
    loadVehicles({{ old('customer_id') }});
@endif
</script>
@endsection
