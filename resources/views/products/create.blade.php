@extends('layouts.app')
@section('title','Tambah Item')
@section('page-title','Tambah Jasa / Sparepart')

@section('content')
<div class="max-w-xl">
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-800">Form Tambah Jasa / Sparepart</h2>
    </div>
    <form action="{{ route('products.store') }}" method="POST" class="p-6 space-y-4" id="product-form">
        @csrf

        {{-- Tipe --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe <span class="text-red-500">*</span></label>
            <div class="flex gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="service"
                        {{ old('type','part') === 'service' ? 'checked' : '' }}
                        class="text-blue-600" onchange="toggleStock(this)">
                    <span class="text-sm text-gray-700">🔧 Jasa / Pekerjaan</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="type" value="part"
                        {{ old('type','part') === 'part' ? 'checked' : '' }}
                        class="text-blue-600" onchange="toggleStock(this)">
                    <span class="text-sm text-gray-700">🔩 Spare Part</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama jasa atau sparepart"
                class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                    {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" rows="2" placeholder="Deskripsi singkat (opsional)"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                <input type="number" name="price" value="{{ old('price',0) }}" min="0" step="500"
                    class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                        {{ $errors->has('price') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('price')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit','pcs') }}"
                    list="unit-list"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <datalist id="unit-list">
                    @foreach(['pcs','liter','kg','set','kali','jam','meter','buah'] as $u)
                        <option value="{{ $u }}">
                    @endforeach
                </datalist>
            </div>
        </div>

        <div id="stock-field">
            <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
            <input type="number" name="stock" value="{{ old('stock',0) }}" min="0"
                class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                    {{ $errors->has('stock') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('stock')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Simpan
            </button>
            <a href="{{ route('products.index') }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Batal</a>
        </div>
    </form>
</div>
</div>

<script>
function toggleStock(radio) {
    const stockField = document.getElementById('stock-field');
    stockField.style.display = radio.value === 'service' ? 'none' : '';
}
// Set initial state
document.addEventListener('DOMContentLoaded', () => {
    const checked = document.querySelector('input[name="type"]:checked');
    if (checked) toggleStock(checked);
});
</script>
@endsection
