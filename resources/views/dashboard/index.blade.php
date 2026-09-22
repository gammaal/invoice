@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')

@section('content')

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Total Work Order</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalInvoices }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua status</p>
    </div>

    <div class="bg-white rounded-xl border border-blue-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Sedang Dikerjakan</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ $inProgress }}</p>
        <p class="text-xs text-gray-400 mt-1">In progress</p>
    </div>

    <div class="bg-white rounded-xl border border-yellow-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Belum Dibayar</p>
        <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $unpaid }}</p>
        <p class="text-xs text-gray-400 mt-1">Selesai & unpaid</p>
    </div>

    <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Total Pendapatan</p>
        <p class="text-xl font-bold text-green-600 mt-1">Rp {{ number_format($totalRevenue,0,',','.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Invoice lunas</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Total Kendaraan</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalVehicles }}</p>
        <p class="text-xs text-gray-400 mt-1">Terdaftar</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Mekanik Aktif</p>
        <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalMechanics }}</p>
        <p class="text-xs text-gray-400 mt-1">Bertugas</p>
    </div>

    <div class="bg-white rounded-xl border border-green-100 shadow-sm p-4">
        <p class="text-xs text-gray-500 font-medium">Lunas</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ $paid }}</p>
        <p class="text-xs text-gray-400 mt-1">Invoice terbayar</p>
    </div>

    <div class="bg-blue-600 rounded-xl shadow-sm p-4 flex flex-col justify-between">
        <p class="text-xs text-blue-200 font-medium">Buat Work Order Baru</p>
        <a href="{{ route('invoices.create') }}"
           class="mt-2 inline-flex items-center text-sm font-semibold text-white hover:text-blue-100">
            + Buat Sekarang →
        </a>
    </div>
</div>

{{-- Tabel Work Order Terbaru --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-800">Work Order Terbaru</h2>
        <a href="{{ route('invoices.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">
            Lihat Semua →
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">No. WO</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Customer</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Kendaraan</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Mekanik</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentInvoices as $inv)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 font-mono text-xs font-semibold text-blue-600">{{ $inv->invoice_number }}</td>
                    <td class="px-5 py-3 text-gray-800">{{ $inv->customer->name ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">
                        @if($inv->vehicle)
                            <span class="font-medium">{{ $inv->vehicle->plate_number }}</span>
                            <span class="text-gray-400 text-xs block">{{ $inv->vehicle->brand }} {{ $inv->vehicle->model }}</span>
                        @else — @endif
                    </td>
                    <td class="px-5 py-3 text-gray-600">{{ $inv->mechanic->name ?? '-' }}</td>
                    <td class="px-5 py-3 font-semibold text-gray-900">Rp {{ number_format($inv->total,0,',','.') }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $inv->status_color }}">
                            {{ $inv->status_label }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('invoices.show',$inv) }}" class="text-xs text-blue-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada work order.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
