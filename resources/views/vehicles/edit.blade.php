@extends('layouts.app')
@section('title','Edit Kendaraan')
@section('page-title','Edit Kendaraan')

@section('content')
<div class="max-w-2xl">
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-800">Edit Kendaraan: <span class="font-mono">{{ $vehicle->plate_number }}</span></h2>
    </div>
    <form action="{{ route('vehicles.update',$vehicle) }}" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Pemilik <span class="text-red-500">*</span></label>
            <select name="customer_id"
                class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                    {{ $errors->has('customer_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ old('customer_id',$vehicle->customer_id)==$c->id ? 'selected' : '' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Plat <span class="text-red-500">*</span></label>
                <input type="text" name="plate_number" value="{{ old('plate_number',$vehicle->plate_number) }}"
                    class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 uppercase
                        {{ $errors->has('plate_number') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('plate_number')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <input type="number" name="year" value="{{ old('year',$vehicle->year) }}"
                    min="1980" max="{{ date('Y')+1 }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Merek <span class="text-red-500">*</span></label>
                <input type="text" name="brand" value="{{ old('brand',$vehicle->brand) }}"
                    list="brand-list"
                    class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                        {{ $errors->has('brand') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                <datalist id="brand-list">
                    @foreach(['Toyota','Honda','Suzuki','Daihatsu','Mitsubishi','Nissan','Isuzu','BMW','Mercedes-Benz','Hyundai','Kia','Wuling','Chery'] as $b)
                        <option value="{{ $b }}">
                    @endforeach
                </datalist>
                @error('brand')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                <input type="text" name="model" value="{{ old('model',$vehicle->model) }}"
                    class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                        {{ $errors->has('model') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('model')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Warna</label>
                <input type="text" name="color" value="{{ old('color',$vehicle->color) }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">No. Mesin</label>
                <input type="text" name="engine_number" value="{{ old('engine_number',$vehicle->engine_number) }}"
                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. Rangka</label>
            <input type="text" name="chassis_number" value="{{ old('chassis_number',$vehicle->chassis_number) }}"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
            <textarea name="notes" rows="2"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('notes',$vehicle->notes) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Perbarui Kendaraan
            </button>
            <a href="{{ route('vehicles.index') }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection
