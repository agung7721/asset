<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use App\Models\PerbaikanLaptop;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaptopExport;
use App\Models\Repair;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatPerbaikan;
use App\Models\Perbaikan;

class ReportController extends Controller
{
    public function daily()
    {
        $data = [
            'total_laptop' => Laptop::count(),
            'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
            'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                ->orWhere('kondisi_awal', 'Rusak Ringan')->count(),
            'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak bisa diperbaiki')
                ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki')->count(),
            'perbaikan_history' => Repair::whereDate('created_at', today())->get()
        ];

        return view('laporan.harian', compact('data'));
    }

    public function downloadExcel(Request $request)
    {
        $period = $request->period ?? 'daily';
        $userName = auth()->user()->name ?? '-';
        return Excel::download(new LaptopExport($period, $userName), 'laporan-laptop.xlsx');
    }

    public function downloadPDF(Request $request)
    {
        $map = [
            'daily' => 'harian',
            'weekly' => 'mingguan',
            'monthly' => 'bulanan',
            'harian' => 'harian',
            'mingguan' => 'mingguan',
            'bulanan' => 'bulanan',
        ];
        $period = $map[$request->period ?? 'harian'] ?? 'harian';
        $data = $this->getLaporanData($period);
        $printedAt = now();
        $userName = auth()->user()->name ?? '-';

        $pdf = PDF::loadView('laporan.pdf.' . $period, [
            'data' => $data,
            'period' => $period,
            'printedAt' => $printedAt,
            'user_name' => $userName
        ]);

        return $pdf->download('laporan-laptop-' . $period . '.pdf');
    }

    private function getLaporanData($period)
    {
        $today = Carbon::today();
        switch($period) {
            case 'mingguan':
            case 'weekly':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                $dailyData = [];
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $dailyData[] = [
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'total_laptop' => Laptop::whereDate('created_at', $currentDate)->count(),
                        'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                            ->whereDate('created_at', $currentDate)->count(),
                        'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                            ->whereDate('created_at', $currentDate)->count(),
                        'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')
                            ->whereDate('created_at', $currentDate)->count(),
                        'jumlah_perbaikan' => Repair::whereDate('created_at', $currentDate)->count(),
                    ];
                    $currentDate->addDay();
                }
                $weeklyTotal = [
                    'perbaikan_history' => Repair::with(['laptop' => function($query) {
                        $query->select('id', 'merk', 'model', 'nama_client');
                    }])
                        ->whereBetween('repairs.created_at', [$startDate, $endDate])
                        ->select('repairs.*')
                        ->get()
                ];
                return [
                    'daily_data' => $dailyData,
                    'weeklyTotal' => $weeklyTotal,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ];
            case 'bulanan':
            case 'monthly':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                $monthlyData = [];
                $currentDate = $startDate->copy();
                while ($currentDate <= $endDate) {
                    $monthlyData[] = [
                        'tanggal' => $currentDate->format('Y-m-d'),
                        'total_laptop' => Laptop::whereDate('created_at', $currentDate)->count(),
                        'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                            ->whereDate('created_at', $currentDate)->count(),
                        'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                            ->whereDate('created_at', $currentDate)->count(),
                        'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')
                            ->whereDate('created_at', $currentDate)->count(),
                        'jumlah_perbaikan' => Repair::whereDate('created_at', $currentDate)->count(),
                    ];
                    $currentDate->addDay();
                }
                $monthlyTotal = [
                    'perbaikan_history' => Repair::with(['laptop' => function($query) {
                        $query->select('id', 'merk', 'model', 'nama_client');
                    }])
                        ->whereBetween('repairs.created_at', [$startDate, $endDate])
                        ->select('repairs.*')
                        ->get()
                ];
                return [
                    'monthly_data' => $monthlyData,
                    'monthlyTotal' => $monthlyTotal,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                ];
            default:
                $startDate = $today;
                $endDate = $today;
                return [
                    'total_laptop' => Laptop::count(),
                    'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')->count(),
                    'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                                       ->orWhere('kondisi_awal', 'Rusak Ringan')->count(),
                    'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak bisa diperbaiki')
                                            ->orWhere('kondisi_awal', 'Tidak bisa diperbaiki')->count(),
                    'perbaikan_history' => Repair::whereBetween('created_at', [$startDate, $endDate])->get(),
                    'date' => $today->format('Y-m-d'),
                ];
        }
    }

    public function weekly(Request $request)
    {
        $date = $request->get('tanggal', now());
        $startDate = Carbon::parse($date)->startOfWeek();
        $endDate = Carbon::parse($date)->endOfWeek();

        $dailyData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dailyData[] = [
                'tanggal' => $currentDate->format('Y-m-d'),
                'total_laptop' => Laptop::whereDate('created_at', $currentDate)->count(),
                'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                    ->whereDate('created_at', $currentDate)->count(),
                'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                    ->whereDate('created_at', $currentDate)->count(),
                'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')
                    ->whereDate('created_at', $currentDate)->count(),
                'jumlah_perbaikan' => Repair::whereDate('created_at', $currentDate)->count(),
            ];
            
            $currentDate->addDay();
        }

        $weeklyTotal = [
            'perbaikan_history' => Repair::with('laptop')
                ->whereBetween('repairs.created_at', [$startDate, $endDate])
                ->select('repairs.*')
                ->get()
        ];

        return view('laporan.mingguan', compact('dailyData', 'weeklyTotal'));
    }

    public function monthly(Request $request)
    {
        $date = $request->get('tanggal', now());
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = Carbon::parse($date)->endOfMonth();

        // Data untuk statistik mingguan dalam satu bulan
        $monthlyData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $weekStart = $currentDate->copy();
            $weekEnd = $currentDate->copy()->endOfWeek()->min($endDate);

            $monthlyData[] = [
                'tanggal' => $weekStart->format('Y-m-d') . ' - ' . $weekEnd->format('Y-m-d'),
                'total_laptop' => Laptop::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')
                    ->whereBetween('created_at', [$weekStart, $weekEnd])->count(),
                'jumlah_perbaikan' => Repair::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            ];

            $currentDate->addWeek();
            if ($currentDate->format('m') !== $startDate->format('m')) {
                break;
            }
        }

        // Total bulanan
        $monthlyTotal = [
            'total_laptop' => Laptop::whereBetween('created_at', [$startDate, $endDate])->count(),
            'dipinjam' => Laptop::where('posisi_terakhir', 'Dipinjam User')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'rusak_ringan' => Laptop::where('kondisi_akhir', 'Rusak Ringan')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'tidak_bisa_diperbaiki' => Laptop::where('kondisi_akhir', 'Tidak Bisa Diperbaiki')
                ->whereBetween('created_at', [$startDate, $endDate])->count(),
            'perbaikan_history' => Repair::with('laptop')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get()
        ];

        return view('laporan.bulanan', compact('monthlyData', 'monthlyTotal'));
    }
}
