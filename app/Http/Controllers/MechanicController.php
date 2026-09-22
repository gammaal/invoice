<?php

namespace App\Http\Controllers;

use App\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $query = Mechanic::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $mechanics = $query->latest()->paginate(15);
        return view('mechanics.index', compact('mechanics'));
    }

    public function create()
    {
        return view('mechanics.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'specialization' => ['nullable', 'string', 'max:100'],
            'is_active'      => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama mekanik wajib diisi.',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        Mechanic::create($validated);

        return redirect()->route('mechanics.index')
            ->with('success', 'Mekanik berhasil ditambahkan.');
    }

    public function edit(Mechanic $mechanic)
    {
        return view('mechanics.edit', compact('mechanic'));
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:100'],
            'phone'          => ['nullable', 'string', 'max:20'],
            'specialization' => ['nullable', 'string', 'max:100'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $mechanic->update($validated);

        return redirect()->route('mechanics.index')
            ->with('success', 'Data mekanik berhasil diperbarui.');
    }

    public function destroy(Mechanic $mechanic)
    {
        if ($mechanic->invoices()->exists()) {
            return back()->with('error', 'Mekanik tidak dapat dihapus karena memiliki riwayat invoice.');
        }
        $mechanic->delete();
        return redirect()->route('mechanics.index')
            ->with('success', 'Mekanik berhasil dihapus.');
    }
}
