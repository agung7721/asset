<?php

namespace App\Exports;

use App\Models\Laptop;
use App\Models\PerbaikanLaptop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaptopExport implements FromCollection, WithHeadings
{
    protected $period;

    public function __construct($period)
    {
        $this->period = $period;
    }

    public function collection()
    {
        $today = Carbon::today();
        
        return collect([[
            $today->format('Y-m-d'),
            Laptop::count(),
            Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
            Laptop::where('kondisi_akhir', 'Rusak Ringan')
                  ->orWhere('kondisi_awal', 'Rusak Ringan')->count(),
            Laptop::where('kondisi_akhir', 'Tidak bisa diperbaiki')
                  ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki')->count(),
            PerbaikanLaptop::whereDate('created_at', $today)->count()
        ]]);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Total Laptop',
            'Dipinjam',
            'Rusak Ringan',
            'Tidak Bisa Diperbaiki',
            'Jumlah Perbaikan'
        ];
    }
}
