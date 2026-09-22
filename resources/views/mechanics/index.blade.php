@extends('layouts.app')
@section('title','Mekanik')
@section('page-title','Data Mekanik')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
        <form method="GET" action="{{ route('mechanics.index') }}" class="flex-1">
            <div class="relative max-w-sm">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama mekanik..."
                    class="w-full pl-8 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-2.5 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </form>
        <a href="{{ route('mechanics.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Mekanik
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">#</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Nama</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Telepon</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Keahlian</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($mechanics as $m)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-3 text-gray-500">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3 font-medium text-gray-900">{{ $m->name }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $m->phone ?? '-' }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $m->specialization ?? '-' }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $m->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $m->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('mechanics.edit',$m) }}"
                               class="px-3 py-1.5 text-xs font-medium bg-yellow-50 text-yellow-700 hover:bg-yellow-100 rounded-lg">Edit</a>
                            <form action="{{ route('mechanics.destroy',$m) }}" method="POST"
                                  onsubmit="return confirm('Hapus mekanik {{ $m->name }}?')">
                                @csrf @method('DELETE')
                                <button class="px-3 py-1.5 text-xs font-medium bg-red-50 text-red-700 hover:bg-red-100 rounded-lg">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-gray-400">Belum ada data mekanik.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($mechanics->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $mechanics->withQueryString()->links() }}</div>
    @endif
</div>
@endsection
