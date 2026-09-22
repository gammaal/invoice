@extends('layouts.app')
@section('title','Work Order')
@section('page-title','Work Order')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">

    {{-- Header --}}
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('invoices.index') }}" class="flex flex-1 flex-wrap gap-2">
            <div class="relative flex-1 min-w-48">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari no. WO, customer, plat nomor..."
                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <select name="status"
                class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                @foreach(['draft'=>'Draft','in_progress'=>'Sedang Dikerjakan','waiting'=>'Menunggu Sparepart','done'=>'Selesai','unpaid'=>'Belum Dibayar','paid'=>'Lunas','cancelled'=>'Dibatalkan'] as $val=>$lbl)
                    <option value="{{ $val }}" {{ request('status')===$val ? 'selected':'' }}>{{ $lbl }}</option>
                @endforeach
            </select>
            <button type="submit"
                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">Filter</button>
        </form>
        <a href="{{ route('invoices.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Work Order
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">No. WO</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Mekanik</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase whitespace-nowrap">Tgl Masuk</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices as $inv)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono font-semibold text-blue-600 text-xs whitespace-nowrap">
                        {{ $inv->invoice_number }}
                    </td>
                    <td class="px-5 py-3 text-gray-800 whitespace-nowrap">
                        {{ $inv->customer->name ?? '-' }}
                    </td>
                    <td class="px-5 py-3">
                        @if($inv->vehicle)
                            <p class="font-mono font-semibold text-gray-900">{{ $inv->vehicle->plate_number }}</p>
                            <p class="text-xs text-gray-400">{{ $inv->vehicle->brand }} {{ $inv->vehicle->model }}</p>
                        @else <span class="text-gray-400">—</span> @endif
                    </td>
                    <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                        {{ $inv->mechanic->name ?? '—' }}
                    </td>
                    <td class="px-5 py-3 text-gray-600 whitespace-nowrap">
                        {{ $inv->invoice_date->format('d M Y') }}
                    </td>
                    <td class="px-5 py-3 font-semibold text-gray-900 whitespace-nowrap">
                        Rp {{ number_format($inv->total,0,',','.') }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $inv->status_color }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <a href="{{ route('invoices.show',$inv) }}"
                               class="px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg">Detail</a>
                            <a href="{{ route('invoices.edit',$inv) }}"
                               class="px-2.5 py-1 text-xs font-medium bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg">Edit</a>
                            <form action="{{ route('invoices.destroy',$inv) }}" method="POST"
                                  onsubmit="return confirm('Hapus WO {{ $inv->invoice_number }}?')">
                                @csrf @method('DELETE')
                                <button class="px-2.5 py-1 text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                        Belum ada work order.
                        <a href="{{ route('invoices.create') }}" class="text-blue-600 hover:underline ml-1">Buat sekarang</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $invoices->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
