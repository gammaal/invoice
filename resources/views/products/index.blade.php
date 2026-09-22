@extends('layouts.app')
@section('title','Jasa & Sparepart')
@section('page-title','Jasa & Sparepart')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-1 gap-2">
            <div class="relative max-w-xs flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama..."
                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select name="type" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Tipe</option>
                <option value="service" {{ request('type')==='service' ? 'selected' : '' }}>Jasa</option>
                <option value="part"    {{ request('type')==='part'    ? 'selected' : '' }}>Spare Part</option>
            </select>
            <button type="submit" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">Filter</button>
        </form>
        <a href="{{ route('products.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Item
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Satuan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Stok</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $p)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $p->type_color }}">
                            {{ $p->type_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $p->name }}</p>
                        @if($p->description)
                            <p class="text-xs text-gray-400 truncate max-w-xs">{{ $p->description }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-900">Rp {{ number_format($p->price,0,',','.') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $p->unit }}</td>
                    <td class="px-5 py-3">
                        @if($p->type === 'service')
                            <span class="text-gray-400 text-xs">—</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $p->stock > 10 ? 'bg-green-100 text-green-700' : ($p->stock > 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ $p->stock }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('products.edit',$p) }}"
                               class="px-3 py-1.5 text-xs font-medium bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg">Edit</a>
                            <form action="{{ route('products.destroy',$p) }}" method="POST"
                                  onsubmit="return confirm('Hapus item {{ $p->name }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada data item.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $products->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
