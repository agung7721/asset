<?php

namespace App\Exports;

use App\Models\Laptop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LaporanExport implements FromCollection, WithHeadings
{
    protected $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection()
    {
        switch($this->type) {
            case 'harian':
                return $this->getHarianData();
            case 'mingguan':
                return $this->getMingguanData();
            case 'bulanan':
                return $this->getBulananData();
            default:
                return collect([]);
        }
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Total Laptop',
            'Dipinjam',
            'Rusak Ringan',
            'Tidak Bisa Diperbaiki'
        ];
    }

    private function getHarianData()
    {
        $today = Carbon::today();
        
        return collect([[
            $today->format('Y-m-d'),
            Laptop::count(),
            Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
            Laptop::where(function($query) {
                $query->where('kondisi_akhir', 'Rusak Ringan')
                      ->orWhere('kondisi_awal', 'Rusak Ringan');
            })->count(),
            Laptop::where(function($query) {
                $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                      ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
            })->count(),
        ]]);
    }

    private function getMingguanData()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $data = [];
        for($date = clone $startOfWeek; $date <= $endOfWeek; $date->addDay()) {
            $data[] = [
                $date->format('Y-m-d'),
                Laptop::whereDate('created_at', '<=', $date)->count(),
                Laptop::where('posisi_terakhir', 'Dipinjam User')
                      ->whereDate('updated_at', $date)->count(),
                Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Rusak Ringan')
                          ->orWhere('kondisi_awal', 'Rusak Ringan');
                })->whereDate('updated_at', $date)->count(),
                Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                          ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                })->whereDate('updated_at', $date)->count(),
            ];
        }

        return collect($data);
    }

    private function getBulananData()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $data = [];
        for($date = clone $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
            $data[] = [
                $date->format('Y-m-d'),
                Laptop::whereDate('created_at', '<=', $date)->count(),
                Laptop::where('posisi_terakhir', 'Dipinjam User')
                      ->whereDate('updated_at', $date)->count(),
                Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Rusak Ringan')
                          ->orWhere('kondisi_awal', 'Rusak Ringan');
                })->whereDate('updated_at', $date)->count(),
                Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                          ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                })->whereDate('updated_at', $date)->count(),
            ];
        }

        return collect($data);
    }
} 