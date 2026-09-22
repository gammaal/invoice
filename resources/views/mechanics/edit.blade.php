@extends('layouts.app')
@section('title','Edit Mekanik')
@section('page-title','Edit Mekanik')

@section('content')
<div class="max-w-lg">
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-6 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-gray-800">Edit Mekanik: {{ $mechanic->name }}</h2>
    </div>
    <form action="{{ route('mechanics.update',$mechanic) }}" method="POST" class="p-6 space-y-4">
        @csrf @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name',$mechanic->name) }}"
                class="w-full px-3 py-2.5 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500
                    {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
            <input type="text" name="phone" value="{{ old('phone',$mechanic->phone) }}"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keahlian / Spesialisasi</label>
            <input type="text" name="specialization" value="{{ old('specialization',$mechanic->specialization) }}"
                list="spec-list"
                class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <datalist id="spec-list">
                @foreach(['Mesin & Transmisi','Kelistrikan','AC & Pendingin','Body & Cat','Kaki-kaki & Rem','General'] as $s)
                    <option value="{{ $s }}">
                @endforeach
            </datalist>
        </div>

        <div class="flex items-center gap-3">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $mechanic->is_active) ? 'checked' : '' }}
                    class="sr-only peer">
                <div class="w-9 h-5 bg-gray-300 rounded-full peer peer-checked:bg-blue-600 transition-colors"></div>
                <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
            </label>
            <span class="text-sm text-gray-700">Mekanik Aktif</span>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Perbarui Mekanik
            </button>
            <a href="{{ route('mechanics.index') }}"
               class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors">Batal</a>
        </div>
    </form>
</div>
</div>
@endsection
