<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\RiwayatLaptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class RiwayatLaptopController extends Controller
{
    public function store(Request $request, Laptop $laptop)
    {
        try {
            $validated = $request->validate([
                'client_baru' => 'required|string|max:255',
                'divisi_baru' => 'nullable|string|max:255',
                'kondisi_akhir' => 'required|in:Baik,Rusak Ringan,Tidak bisa diperbaiki',
                'tanggal_perpindahan' => 'required|date',
                'keterangan' => 'nullable|string'
            ]);

            $validated['client_lama'] = $laptop->nama_client;
            $validated['divisi_lama'] = $laptop->divisi;
            
            DB::beginTransaction();
            
            $laptop->riwayat()->create($validated);

            $laptop->update([
                'nama_client' => $validated['client_baru'],
                'divisi' => $validated['divisi_baru'],
                'kondisi_akhir' => $validated['kondisi_akhir']
            ]);

            DB::commit();
            return redirect()->route('laptops.show', $laptop->id)
                ->with('success', 'Data perpindahan berhasil ditambahkan');
            
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('warning', 'Periksa kembali data yang diinput')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }

    public function destroy(Laptop $laptop, RiwayatLaptop $riwayat)
    {
        try {
            $riwayat->delete();
            return redirect()->route('laptops.show', $laptop->id)
                ->with('success', 'Data riwayat perpindahan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data riwayat');
        }
    }

    public function update(Request $request, Laptop $laptop, RiwayatLaptop $riwayat)
    {
        try {
            $validated = $request->validate([
                'client_baru' => 'required|string|max:255',
                'divisi_baru' => 'nullable|string|max:255',
                'kondisi_akhir' => 'required|in:Baik,Rusak Ringan,Tidak bisa diperbaiki',
                'tanggal_perpindahan' => 'required|date',
                'keterangan' => 'nullable|string'
            ]);
            
            DB::beginTransaction();
            
            $riwayat->update($validated);
            
            // Update laptop jika ini adalah riwayat terbaru
            if ($riwayat->id === $laptop->riwayat()->latest()->first()->id) {
                $laptop->update([
                    'nama_client' => $validated['client_baru'],
                    'divisi' => $validated['divisi_baru'],
                    'kondisi_akhir' => $validated['kondisi_akhir']
                ]);
            }

            DB::commit();
            return redirect()->route('laptops.show', $laptop->id)
                ->with('success', 'Data riwayat berhasil diperbarui');
            
        } catch (ValidationException $e) {
            return redirect()->back()
                ->with('warning', 'Periksa kembali data yang diinput')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }
}
