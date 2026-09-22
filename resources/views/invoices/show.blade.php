@extends('layouts.app')
@section('title', $invoice->invoice_number)
@section('page-title','Detail Work Order')

@section('content')
<div class="max-w-4xl space-y-4">

    {{-- Action Bar --}}
    <div class="flex flex-wrap items-center gap-2">
        <a href="{{ route('invoices.index') }}"
           class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 mr-1">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg> Kembali
        </a>
        <a href="{{ route('invoices.edit',$invoice) }}"
           class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-lg transition-colors">
            Edit
        </a>
        <a href="{{ route('invoices.print',$invoice) }}" target="_blank"
           class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
            🖨️ Cetak
        </a>
        <form action="{{ route('invoices.destroy',$invoice) }}" method="POST"
              onsubmit="return confirm('Hapus work order ini?')" class="ml-auto">
            @csrf @method('DELETE')
            <button type="submit"
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                Hapus
            </button>
        </form>
    </div>

    {{-- Header WO --}}
    <div class="bg-gray-900 text-white rounded-xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Work Order</p>
            <p class="text-2xl font-bold font-mono text-blue-300">{{ $invoice->invoice_number }}</p>
            <p class="text-sm text-gray-400 mt-1">
                Masuk: {{ $invoice->invoice_date->format('d M Y') }}
                &nbsp;·&nbsp;
                Est. Selesai: {{ $invoice->due_date->format('d M Y') }}
            </p>
        </div>
        <span class="inline-block px-4 py-1.5 rounded-full text-sm font-bold {{ $invoice->status_color }}">
            {{ strtoupper($invoice->status_label) }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Info Customer --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Pemilik Kendaraan</p>
            <p class="font-bold text-gray-900">{{ $invoice->customer->name ?? '-' }}</p>
            @if($invoice->customer)
                <p class="text-sm text-gray-600 mt-0.5">{{ $invoice->customer->email }}</p>
                @if($invoice->customer->phone)
                    <p class="text-sm text-gray-600">{{ $invoice->customer->phone }}</p>
                @endif
                @if($invoice->customer->address)
                    <p class="text-sm text-gray-500 mt-1">{{ $invoice->customer->address }}</p>
                @endif
            @endif
        </div>

        {{-- Info Kendaraan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Data Kendaraan</p>
            @if($invoice->vehicle)
                <p class="font-bold text-gray-900 text-lg font-mono">{{ $invoice->vehicle->plate_number }}</p>
                <p class="text-sm text-gray-700 mt-0.5">
                    {{ $invoice->vehicle->brand }} {{ $invoice->vehicle->model }}
                    @if($invoice->vehicle->year)({{ $invoice->vehicle->year }})@endif
                    @if($invoice->vehicle->color)— {{ $invoice->vehicle->color }}@endif
                </p>
                @if($invoice->vehicle->engine_number)
                    <p class="text-xs text-gray-400 mt-1">No. Mesin: {{ $invoice->vehicle->engine_number }}</p>
                @endif
                <div class="flex gap-4 mt-2 text-xs text-gray-500">
                    @if($invoice->mileage_in)
                        <span>KM Masuk: <strong class="text-gray-800">{{ number_format($invoice->mileage_in) }}</strong></span>
                    @endif
                    @if($invoice->mileage_out)
                        <span>KM Keluar: <strong class="text-gray-800">{{ number_format($invoice->mileage_out) }}</strong></span>
                    @endif
                </div>
            @else
                <p class="text-gray-400 text-sm">—</p>
            @endif
        </div>

        {{-- Keluhan & Diagnosa --}}
        @if($invoice->complaint || $invoice->diagnosis || $invoice->vehicle_condition)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 md:col-span-2">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Keluhan & Diagnosa</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                @if($invoice->complaint)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Keluhan Pelanggan</p>
                    <p class="text-gray-800">{{ $invoice->complaint }}</p>
                </div>
                @endif
                @if($invoice->diagnosis)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Diagnosa Mekanik</p>
                    <p class="text-gray-800">{{ $invoice->diagnosis }}</p>
                </div>
                @endif
                @if($invoice->vehicle_condition)
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">Kondisi Kendaraan</p>
                    <p class="text-gray-800">{{ $invoice->vehicle_condition }}</p>
                </div>
                @endif
            </div>
            @if($invoice->mechanic)
            <p class="text-xs text-gray-400 mt-3">
                Dikerjakan oleh: <strong class="text-gray-700">{{ $invoice->mechanic->name }}</strong>
                @if($invoice->mechanic->specialization)({{ $invoice->mechanic->specialization }})@endif
            </p>
            @endif
        </div>
        @endif
    </div>

    {{-- Tabel Item --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Pekerjaan & Sparepart</p>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Item</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Qty</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Harga</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($invoice->details as $d)
                <tr>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $d->product->name ?? '—' }}</td>
                    <td class="px-5 py-3">
                        @if($d->product)
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $d->product->type_color }}">
                                {{ $d->product->type_label }}
                            </span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-center text-gray-700">
                        {{ $d->quantity }}
                        @if($d->product?->unit)
                            <span class="text-xs text-gray-400">{{ $d->product->unit }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right text-gray-700">Rp {{ number_format($d->price,0,',','.') }}</td>
                    <td class="px-5 py-3 text-right font-semibold text-gray-900">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total --}}
        <div class="px-5 py-4 border-t border-gray-100 flex justify-end">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="text-gray-900">Rp {{ number_format($invoice->subtotal,0,',','.') }}</span>
                </div>
                @if($invoice->discount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Diskon</span>
                    <span class="text-red-600">- Rp {{ number_format($invoice->discount,0,',','.') }}</span>
                </div>
                @endif
                @if($invoice->tax > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Pajak / PPN</span>
                    <span class="text-gray-900">Rp {{ number_format($invoice->tax,0,',','.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2 border-t-2 border-gray-900">
                    <span class="font-bold text-gray-900 text-base">TOTAL</span>
                    <span class="font-bold text-gray-900 text-xl">Rp {{ number_format($invoice->total,0,',','.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
