<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\LaptopImage;
use App\Models\LaptopHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $query = Laptop::query();

        // Filter berdasarkan posisi terakhir
        if ($request->has('posisi_terakhir')) {
            $query->where('posisi_terakhir', $request->posisi_terakhir);
        }

        // Filter berdasarkan kondisi jika ada request
        if ($request->has('kondisi') && $request->kondisi != '') {
            $query->where(function($q) use ($request) {
                $q->where('kondisi_akhir', $request->kondisi)
                  ->orWhere('kondisi_awal', $request->kondisi);
            });
        }

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('posisi_terakhir', $request->status);
        }

        // Filter berdasarkan kondisi akhir
        if ($request->has('kondisi_akhir')) {
            $query->where('kondisi_akhir', $request->kondisi_akhir);
        }

        // Filter berdasarkan rentang tanggal created_at
        if ($request->has('created_at_start') && $request->has('created_at_end')) {
            $query->whereBetween('created_at', [$request->created_at_start, $request->created_at_end]);
        }

        // Fitur pencarian berdasarkan kolom client
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_client', 'like', "%$search%")
                  // tambahkan kolom lain jika ingin search multi kolom
                  ;
            });
        }

        $laptops = $query->paginate(10);

        return view('laptops.index', compact('laptops'));
    }

    public function create()
    {
        // Generate nomor asset saat halaman dimuat
        $today = now();
        $dateFormat = $today->format('Y-m-d');
        
        // Cari nomor terakhir untuk hari ini
        $lastAsset = Laptop::where('nomor_asset', 'LIKE', "KHZ-ASSET-LTP-{$dateFormat}-%")
            ->orderBy('created_at', 'desc')
            ->first();

        // Generate nomor berikutnya
        if ($lastAsset) {
            $lastNumber = (int) substr($lastAsset->nomor_asset, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        $nomorAsset = "KHZ-ASSET-LTP-{$dateFormat}-{$formattedNumber}";

        return view('laptops.create', compact('nomorAsset'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'kapasitas_ssd' => 'required|numeric|min:1',
            'kapasitas_ram' => 'required|numeric|min:1',
            'tanggal_pembelian' => 'nullable|date',
            'nama_client' => 'nullable|string|max:255',
            'divisi' => 'nullable|string|max:255',
            'tanggal_penyerahan' => 'nullable|date',
            'kondisi_awal' => 'required|string',
            'kondisi_akhir' => 'nullable|string',
            'posisi_terakhir' => 'required|string'
        ]);

        try {
            // Tambahkan nomor asset ke data yang divalidasi
            $validated['nomor_asset'] = $request->nomor_asset;
            
            $laptop = Laptop::create($validated);

            return redirect()
                ->route('laptops.index')
                ->with('success', 'Data laptop berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error saat menyimpan laptop: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    public function show(Laptop $laptop)
    {
        $laptop->load(['images', 'histories']);
        return view('laptops.show', compact('laptop'));
    }

    public function edit(Laptop $laptop)
    {
        return view('laptops.edit', compact('laptop'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        $validated = $request->validate([
            'merk' => 'required',
            'model' => 'required',
            'serial_number' => 'required',
            'kapasitas_ssd' => 'required|numeric',
            'kapasitas_ram' => 'required|numeric',
            'kondisi_awal' => 'required',
            'kondisi_akhir' => 'nullable',
            'keterangan_rusak' => 'required_if:kondisi_akhir,Rusak Berat',
            'posisi_terakhir' => 'required',
        ]);

        try {
            // Jika kondisi akhir bukan Rusak Berat, kosongkan keterangan
            if ($validated['kondisi_akhir'] !== 'Rusak Berat') {
                $validated['keterangan_rusak'] = null;
            }

            $laptop->update($validated);
            
            return redirect()
                ->route('laptops.index')
                ->with('success', 'Data laptop berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating laptop: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data');
        }
    }

    public function destroy(Laptop $laptop)
    {
        // Hapus semua gambar terkait
        foreach ($laptop->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $laptop->delete();

        return redirect()
            ->route('laptops.index')
            ->with('success', 'Laptop berhasil dihapus');
    }

    public function uploadImage(Request $request, Laptop $laptop)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'type' => 'required|in:tanda_terima,riwayat'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('laptop-images', 'public');
            
            $laptop->images()->create([
                'image_path' => $path,
                'type' => $request->type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Gambar berhasil diupload'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal upload gambar'
        ], 400);
    }

    protected function validateRequest()
    {
        return request()->validate([
            'merk' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'tanggal_pembelian' => 'nullable|date',
            'nama_client' => 'nullable|string|max:255',
            'tanggal_penyerahan' => 'nullable|date',
            'kondisi_awal' => 'required|string',
            'posisi_terakhir' => 'required|string',
            'tanda_terima' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'divisi' => 'nullable|string|max:255',
        ]);
    }

    public function getLastAssetNumber()
    {
        try {
            $today = now();
            $dateFormat = $today->format('Y-m-d');
            
            // Cari nomor terakhir untuk hari ini
            $lastAsset = Laptop::where('nomor_asset', 'LIKE', "KHZ-ASSET-LTP-{$dateFormat}-%")
                ->orderBy('id', 'desc')
                ->first();

            // Generate nomor berikutnya
            if ($lastAsset) {
                $lastNumber = (int) substr($lastAsset->nomor_asset, -4);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            $nomorAsset = "KHZ-ASSET-LTP-{$dateFormat}-{$formattedNumber}";

            // Log untuk debugging
            \Log::info('Generated asset number', ['nomor' => $nomorAsset]);

            return response()->json([
                'status' => 'success',
                'nomor_asset' => $nomorAsset
            ], 200, [], JSON_UNESCAPED_SLASHES);

        } catch (\Exception $e) {
            \Log::error('Error generating asset number: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500, [], JSON_UNESCAPED_SLASHES);
        }
    }

    public function storeRiwayat(Request $request, Laptop $laptop)
    {
        $validated = $request->validate([
            'client_lama' => 'nullable|string|max:255',
            'divisi_lama' => 'nullable|string|max:255',
            'client_baru' => 'nullable|string|max:255',
            'divisi_baru' => 'nullable|string|max:255',
            'kondisi_akhir' => 'nullable|string',
            'tanggal_perpindahan' => 'nullable|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            // Proses penyimpanan data
            $riwayat = $laptop->riwayatPerpindahan()->create($validated);

            // Update data laptop hanya jika ada perubahan
            if ($request->filled('client_baru')) {
                $laptop->update(['nama_client' => $request->client_baru]);
            }
            if ($request->filled('divisi_baru')) {
                $laptop->update(['divisi' => $request->divisi_baru]);
            }
            if ($request->filled('kondisi_akhir')) {
                $laptop->update(['kondisi_akhir' => $request->kondisi_akhir]);
            }

            return redirect()
                ->route('laptops.show', $laptop->id)
                ->with('perpindahan_success', 'Riwayat perpindahan berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data');
        }
    }
}
