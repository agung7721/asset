<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLaptop = Laptop::count();
        $laptopDipinjam = Laptop::where('posisi_terakhir', 'Dipinjam User')->count();
        $laptopPerbaikan = Laptop::where('posisi_terakhir', 'Perbaikan')->count();
        $laptopGudang = Laptop::where('posisi_terakhir', 'Gudang IT')->count();
        $laptopRusak = Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')->count();

        return view('dashboard', compact(
            'totalLaptop',
            'laptopDipinjam',
            'laptopPerbaikan',
            'laptopGudang',
            'laptopRusak'
        ));
    }
}
