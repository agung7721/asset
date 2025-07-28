<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Laptop;
use Illuminate\Http\Request;

class RepairController extends Controller
{
    public function destroy(Laptop $laptop, $repairId)
    {
        try {
            $repair = Repair::where('laptop_id', $laptop->id)
                           ->where('id', $repairId)
                           ->firstOrFail();
                           
            $repair->delete();
            
            return redirect()->back()
                           ->with('success', 'Data perbaikan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Gagal menghapus data perbaikan: ' . $e->getMessage());
        }
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
            $laptop->repairs()->create($validated);

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
}
