<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\TandaTerima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TandaTerimaController extends Controller
{
    public function store(Request $request, Laptop $laptop)
    {
        $request->validate([
            'tanda_terima.*' => 'required|image|max:25600', // max 25MB (25600KB)
        ]);

        if ($request->hasFile('tanda_terima')) {
            foreach ($request->file('tanda_terima') as $file) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tanda-terima', $filename, 'public');
                
                $laptop->tandaTerima()->create([
                    'path' => $path,
                    'nama_file' => $file->getClientOriginalName(),
                    'ukuran' => $file->getSize(),
                    'tipe' => 'image'
                ]);
            }

            return redirect()->back()->with('success', 'Foto berhasil diupload');
        }

        return redirect()->back()->with('error', 'Terjadi kesalahan saat upload foto');
    }

    public function destroy(Laptop $laptop, $id)
    {
        $tandaTerima = TandaTerima::findOrFail($id);
        
        // Hapus file fisik
        if (Storage::disk('public')->exists($tandaTerima->path)) {
            Storage::disk('public')->delete($tandaTerima->path);
        }
        
        // Hapus data dari database
        $tandaTerima->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Foto berhasil dihapus');
    }
}
