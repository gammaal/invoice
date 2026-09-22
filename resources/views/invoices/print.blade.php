<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body class="bg-white font-sans text-gray-900 p-6">

{{-- Tombol cetak --}}
<div class="no-print mb-5 flex gap-3">
    <button onclick="window.print()"
        class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg">🖨️ Cetak</button>
    <a href="{{ route('invoices.show',$invoice) }}"
       class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg">← Kembali</a>
</div>

<div class="max-w-3xl mx-auto border border-gray-200 rounded-xl overflow-hidden shadow-sm">

    {{-- Header --}}
    <div class="bg-gray-900 text-white px-8 py-5 flex items-start justify-between">
        <div>
            <p class="text-sm font-bold text-gray-300 uppercase tracking-widest">Bengkel App</p>
            <p class="text-xs text-gray-500">Jl. Raya Bengkel No. 1, Kota Anda</p>
            <p class="text-xs text-gray-500">Telp: 021-5551234</p>
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-400 uppercase tracking-widest mb-1">Work Order</p>
            <p class="text-xl font-bold font-mono text-blue-300">{{ $invoice->invoice_number }}</p>
            <span class="inline-block mt-1 px-3 py-0.5 rounded-full text-xs font-bold
                {{ $invoice->status==='paid' ? 'bg-green-500 text-white' :
                   ($invoice->status==='unpaid'||$invoice->status==='done' ? 'bg-yellow-400 text-gray-900' :
                   ($invoice->status==='cancelled' ? 'bg-red-500 text-white' :
                   ($invoice->status==='in_progress' ? 'bg-blue-500 text-white' : 'bg-gray-600 text-white'))) }}">
                {{ strtoupper($invoice->status_label) }}
            </span>
        </div>
    </div>

    <div class="p-8">

        {{-- Info --}}
        <div class="grid grid-cols-2 gap-6 mb-6">

            {{-- Customer --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pemilik</p>
                <p class="font-bold text-gray-900">{{ $invoice->customer->name ?? '-' }}</p>
                @if($invoice->customer)
                    <p class="text-sm text-gray-600">{{ $invoice->customer->email }}</p>
                    @if($invoice->customer->phone)
                        <p class="text-sm text-gray-600">{{ $invoice->customer->phone }}</p>
                    @endif
                    @if($invoice->customer->address)
                        <p class="text-sm text-gray-500 mt-1">{{ $invoice->customer->address }}</p>
                    @endif
                @endif
            </div>

            {{-- Kendaraan --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kendaraan</p>
                @if($invoice->vehicle)
                    <p class="font-bold text-gray-900 text-lg font-mono">{{ $invoice->vehicle->plate_number }}</p>
                    <p class="text-sm text-gray-700">
                        {{ $invoice->vehicle->brand }} {{ $invoice->vehicle->model }}
                        @if($invoice->vehicle->year)({{ $invoice->vehicle->year }})@endif
                    </p>
                    @if($invoice->vehicle->color)
                        <p class="text-xs text-gray-500">Warna: {{ $invoice->vehicle->color }}</p>
                    @endif
                    @if($invoice->vehicle->engine_number)
                        <p class="text-xs text-gray-500">No. Mesin: {{ $invoice->vehicle->engine_number }}</p>
                    @endif
                @else <p class="text-gray-400">—</p> @endif
            </div>

            {{-- Tanggal & KM --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal</p>
                <p class="text-sm text-gray-700">Masuk: <strong>{{ $invoice->invoice_date->format('d M Y') }}</strong></p>
                <p class="text-sm text-gray-700">Est. Selesai: <strong>{{ $invoice->due_date->format('d M Y') }}</strong></p>
                @if($invoice->mileage_in || $invoice->mileage_out)
                <p class="text-sm text-gray-700 mt-1">
                    KM Masuk: <strong>{{ number_format($invoice->mileage_in ?? 0) }}</strong>
                    @if($invoice->mileage_out)
                    | KM Keluar: <strong>{{ number_format($invoice->mileage_out) }}</strong>
                    @endif
                </p>
                @endif
            </div>

            {{-- Mekanik --}}
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Mekanik</p>
                @if($invoice->mechanic)
                    <p class="font-semibold text-gray-900">{{ $invoice->mechanic->name }}</p>
                    @if($invoice->mechanic->specialization)
                        <p class="text-xs text-gray-500">{{ $invoice->mechanic->specialization }}</p>
                    @endif
                @else <p class="text-gray-400 text-sm">—</p> @endif
            </div>
        </div>

        {{-- Keluhan --}}
        @if($invoice->complaint || $invoice->diagnosis)
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100 grid grid-cols-2 gap-4 text-sm">
            @if($invoice->complaint)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Keluhan</p>
                <p class="text-gray-700">{{ $invoice->complaint }}</p>
            </div>
            @endif
            @if($invoice->diagnosis)
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase mb-1">Diagnosa</p>
                <p class="text-gray-700">{{ $invoice->diagnosis }}</p>
            </div>
            @endif
        </div>
        @endif

        {{-- Tabel Item --}}
        <table class="w-full text-sm mb-6 border-collapse">
            <thead>
                <tr class="bg-gray-900 text-white">
                    <th class="px-4 py-2.5 text-left text-xs uppercase font-semibold rounded-tl-lg">Item</th>
                    <th class="px-4 py-2.5 text-left text-xs uppercase font-semibold">Tipe</th>
                    <th class="px-4 py-2.5 text-center text-xs uppercase font-semibold w-20">Qty</th>
                    <th class="px-4 py-2.5 text-right text-xs uppercase font-semibold">Harga</th>
                    <th class="px-4 py-2.5 text-right text-xs uppercase font-semibold rounded-tr-lg">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->details as $i => $d)
                <tr class="{{ $i%2===0 ? 'bg-white' : 'bg-gray-50' }} border-b border-gray-100">
                    <td class="px-4 py-2.5 font-medium text-gray-900">{{ $d->product->name ?? '—' }}</td>
                    <td class="px-4 py-2.5">
                        @if($d->product)
                            <span class="text-xs font-medium {{ $d->product->type==='service' ? 'text-blue-600' : 'text-orange-600' }}">
                                {{ $d->product->type_label }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-center text-gray-700">
                        {{ $d->quantity }}{{ $d->product?->unit ? ' '.$d->product->unit : '' }}
                    </td>
                    <td class="px-4 py-2.5 text-right text-gray-700">Rp {{ number_format($d->price,0,',','.') }}</td>
                    <td class="px-4 py-2.5 text-right font-semibold text-gray-900">Rp {{ number_format($d->subtotal,0,',','.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Total --}}
        <div class="flex justify-end mb-8">
            <div class="w-64 space-y-1.5 text-sm">
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-500">Subtotal</span>
                    <span>Rp {{ number_format($invoice->subtotal,0,',','.') }}</span>
                </div>
                @if($invoice->discount > 0)
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-500">Diskon</span>
                    <span class="text-red-600">- Rp {{ number_format($invoice->discount,0,',','.') }}</span>
                </div>
                @endif
                @if($invoice->tax > 0)
                <div class="flex justify-between py-1 border-b border-gray-100">
                    <span class="text-gray-500">Pajak / PPN</span>
                    <span>Rp {{ number_format($invoice->tax,0,',','.') }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-2">
                    <span class="font-bold text-base">TOTAL</span>
                    <span class="font-bold text-lg">Rp {{ number_format($invoice->total,0,',','.') }}</span>
                </div>
            </div>
        </div>

        {{-- Tanda Tangan --}}
        <div class="grid grid-cols-3 gap-6 text-center text-xs text-gray-500 mt-4">
            <div>
                <p class="mb-12">Pelanggan,</p>
                <div class="border-t border-gray-300 pt-1">{{ $invoice->customer->name ?? '—' }}</div>
            </div>
            <div>
                <p class="mb-12">Mekanik,</p>
                <div class="border-t border-gray-300 pt-1">{{ $invoice->mechanic->name ?? '—' }}</div>
            </div>
            <div>
                <p class="mb-12">Kasir / Admin,</p>
                <div class="border-t border-gray-300 pt-1">( ________________ )</div>
            </div>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-gray-400 mt-8 pt-4 border-t border-gray-100">
            Dicetak pada {{ now()->format('d M Y H:i') }} · Terima kasih telah mempercayakan kendaraan Anda kepada kami.
        </p>
    </div>
</div>

</body>
</html>
