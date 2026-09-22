@extends('layouts.app')
@section('title','Kendaraan')
@section('page-title','Data Kendaraan')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">

    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('vehicles.index') }}" class="flex-1">
            <div class="relative max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari plat, merek, model, customer..."
                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
        <a href="{{ route('vehicles.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kendaraan
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Plat Nomor</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Tahun</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Warna</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Pemilik</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($vehicles as $v)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono font-bold text-gray-900">{{ $v->plate_number }}</td>
                    <td class="px-5 py-3">
                        <p class="font-medium text-gray-900">{{ $v->brand }} {{ $v->model }}</p>
                        @if($v->color)<p class="text-xs text-gray-400">{{ $v->color }}</p>@endif
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $v->year ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $v->color ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-700">{{ $v->customer->name ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('vehicles.edit',$v) }}"
                               class="px-3 py-1.5 text-xs font-medium bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg">Edit</a>
                            <form action="{{ route('vehicles.destroy',$v) }}" method="POST"
                                  onsubmit="return confirm('Hapus kendaraan {{ $v->plate_number }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada kendaraan terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($vehicles->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $vehicles->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
