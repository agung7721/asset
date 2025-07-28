<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\Repair;
use App\Models\LaptopHistory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function harian()
    {
        $today = Carbon::today();
        
        $data = [
            'total_laptop' => Laptop::count(),
            'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
            'rusak_ringan' => Laptop::where(function($query) {
                $query->where('kondisi_akhir', 'Rusak Ringan')
                      ->orWhere('kondisi_awal', 'Rusak Ringan');
            })->count(),
            'tidak_bisa_diperbaiki' => Laptop::where(function($query) {
                $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                      ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
            })->count(),
            'perbaikan_history' => Repair::whereDate('created_at', $today)
                ->with(['laptop' => function($query) {
                    $query->select('id', 'merk', 'model');
                }])
                ->get(),
            'perpindahan_history' => LaptopHistory::whereDate('created_at', $today)
                ->with(['laptop' => function($query) {
                    $query->select('id', 'merk', 'model');
                }])
                ->get()
        ];

        return view('laporan.harian', compact('data'));
    }

    public function mingguan()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $dailyData = [];
        for($date = $startOfWeek; $date <= $endOfWeek; $date->addDay()) {
            $dailyData[] = [
                'tanggal' => $date->format('Y-m-d'),
                'total_laptop' => Laptop::whereDate('created_at', '<=', $date)->count(),
                'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                                   ->whereDate('updated_at', $date)->count(),
                'rusak_ringan' => Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Rusak Ringan')
                          ->orWhere('kondisi_awal', 'Rusak Ringan');
                })->whereDate('updated_at', $date)->count(),
                'tidak_bisa_diperbaiki' => Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                          ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                })->whereDate('updated_at', $date)->count(),
            ];
        }

        return view('laporan.mingguan', compact('dailyData'));
    }

    public function bulanan()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        
        $monthlyData = [];
        for($date = $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
            $monthlyData[] = [
                'tanggal' => $date->format('Y-m-d'),
                'total_laptop' => Laptop::whereDate('created_at', '<=', $date)->count(),
                'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                                   ->whereDate('updated_at', $date)->count(),
                'rusak_ringan' => Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Rusak Ringan')
                          ->orWhere('kondisi_awal', 'Rusak Ringan');
                })->whereDate('updated_at', $date)->count(),
                'tidak_bisa_diperbaiki' => Laptop::where(function($query) use ($date) {
                    $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                          ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                })->whereDate('updated_at', $date)->count(),
            ];
        }

        return view('laporan.bulanan', compact('monthlyData'));
    }

    public function downloadExcel(Request $request)
    {
        return Excel::download(new LaporanExport($request->type), 'laporan-'.$request->type.'.xlsx');
    }

    public function downloadPDF(Request $request)
    {
        $data = $this->getLaporanData($request->type);
        $view = 'laporan.pdf.' . $request->type;
        $printedAt = now();
        $pdf = Pdf::loadView($view, compact('data', 'printedAt'));
        return $pdf->download('laporan-' . $request->type . '.pdf');
    }

    private function getLaporanData($type)
    {
        switch($type) {
            case 'harian':
                $today = Carbon::today();
                return [
                    'type' => 'harian',
                    'date' => $today->format('Y-m-d'),
                    'total_laptop' => Laptop::count(),
                    'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
                    'rusak_ringan' => Laptop::where(function($query) {
                        $query->where('kondisi_akhir', 'Rusak Ringan')
                              ->orWhere('kondisi_awal', 'Rusak Ringan');
                    })->count(),
                    'tidak_bisa_diperbaiki' => Laptop::where(function($query) {
                        $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                              ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                    })->count(),
                    'perbaikan_history' => Repair::whereDate('created_at', $today)
                        ->with(['laptop' => function($query) {
                            $query->select('id', 'merk', 'model', 'nama_client');
                        }])
                        ->get(),
                    'perpindahan_history' => LaptopHistory::whereDate('created_at', $today)
                        ->with(['laptop' => function($query) {
                            $query->select('id', 'merk', 'model');
                        }])
                        ->get()
                ];

            case 'mingguan':
                $startOfWeek = Carbon::now()->startOfWeek();
                $endOfWeek = Carbon::now()->endOfWeek();
                
                $dailyData = [];
                for($date = clone $startOfWeek; $date <= $endOfWeek; $date->addDay()) {
                    $dailyData[] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'total_laptop' => Laptop::whereDate('created_at', '<=', $date)->count(),
                        'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                                           ->whereDate('updated_at', $date)->count(),
                        'rusak_ringan' => Laptop::where(function($query) use ($date) {
                            $query->where('kondisi_akhir', 'Rusak Ringan')
                                  ->orWhere('kondisi_awal', 'Rusak Ringan');
                        })->whereDate('updated_at', $date)->count(),
                        'tidak_bisa_diperbaiki' => Laptop::where(function($query) use ($date) {
                            $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                                  ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                        })->whereDate('updated_at', $date)->count(),
                    ];
                }
                return [
                    'type' => 'mingguan',
                    'start_date' => $startOfWeek->format('Y-m-d'),
                    'end_date' => $endOfWeek->format('Y-m-d'),
                    'daily_data' => $dailyData
                ];

            case 'bulanan':
                $startOfMonth = Carbon::now()->startOfMonth();
                $endOfMonth = Carbon::now()->endOfMonth();
                
                $monthlyData = [];
                for($date = clone $startOfMonth; $date <= $endOfMonth; $date->addDay()) {
                    $monthlyData[] = [
                        'tanggal' => $date->format('Y-m-d'),
                        'total_laptop' => Laptop::whereDate('created_at', '<=', $date)->count(),
                        'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                                           ->whereDate('updated_at', $date)->count(),
                        'rusak_ringan' => Laptop::where(function($query) use ($date) {
                            $query->where('kondisi_akhir', 'Rusak Ringan')
                                  ->orWhere('kondisi_awal', 'Rusak Ringan');
                        })->whereDate('updated_at', $date)->count(),
                        'tidak_bisa_diperbaiki' => Laptop::where(function($query) use ($date) {
                            $query->where('kondisi_akhir', 'Tidak bisa diperbaiki')
                                  ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki');
                        })->whereDate('updated_at', $date)->count(),
                    ];
                }
                return [
                    'type' => 'bulanan',
                    'start_date' => $startOfMonth->format('Y-m-d'),
                    'end_date' => $endOfMonth->format('Y-m-d'),
                    'monthly_data' => $monthlyData
                ];

            default:
                return [];
        }
    }
}
