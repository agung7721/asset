<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\PerbaikanLaptop;
use Illuminate\Http\Request;

class PerbaikanLaptopController extends Controller
{
    public function create(Laptop $laptop)
    {
        return view('perbaikan.create', compact('laptop'));
    }

    public function store(Request $request, Laptop $laptop)
    {
        $validated = $request->validate([
            'tanggal_perbaikan' => 'required|date',
            'nama_sparepart' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string'
        ]);

        try {
            $laptop->perbaikan()->create($validated);

            return redirect()
                ->route('laptops.show', $laptop->id)
                ->with('success', 'Data perbaikan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy(Laptop $laptop, PerbaikanLaptop $perbaikan)
    {
        $perbaikan->delete();
        return redirect()->route('laptops.show', $laptop->id)
            ->with('success', 'Data perbaikan berhasil dihapus');
    }
}
