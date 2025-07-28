@extends('adminlte::page')

@section('title', 'Laporan Bulanan')

@section('content_header')
    <h1>Laporan Bulanan - {{ \Carbon\Carbon::parse($monthlyTotal['periode_awal'] ?? now())->translatedFormat('F Y') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Bulanan</h3>
                <div class="card-tools">
                    <a href="{{ route('reports.download.excel', ['period' => 'monthly']) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                    <a href="{{ route('reports.download.pdf', ['period' => 'monthly']) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="mb-3" style="display: flex; align-items: center; gap: 10px;">
                    <div style="background: #e3f0fa; color: #1976d2; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fas fa-info"></i>
                    </div>
                    <div style="font-size: 15px; color: #1976d2; font-weight: 500;">
                        Grafik di bawah ini menampilkan statistik bulanan jumlah laptop, status peminjaman, kerusakan ringan, dan yang tidak bisa diperbaiki. Data diambil per minggu dalam satu bulan.
                    </div>
                </div>
                <div style="width: 100%; max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #f8f9fa 60%, #e3f0fa 100%); border-radius: 18px; box-shadow: 0 4px 24px rgba(25, 118, 210, 0.08); padding: 24px 18px 18px 18px; border: 1.5px solid #e3f0fa;">
                    <canvas id="statistikBulanan" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Bulan Ini</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        @php
                            $periodeAwal = isset($monthlyData[0]) ? \Carbon\Carbon::parse(explode(' - ', $monthlyData[0]['tanggal'])[0])->format('Y-m-d') : null;
                            $periodeAkhir = isset($monthlyData[count($monthlyData)-1]) ? \Carbon\Carbon::parse(explode(' - ', $monthlyData[count($monthlyData)-1]['tanggal'])[1])->format('Y-m-d') : null;
                        @endphp
                        <a href="{{ route('laptops.index', ['created_at_start' => $periodeAwal, 'created_at_end' => $periodeAkhir]) }}" style="text-decoration:none; color:inherit;">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-laptop"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Laptop</span>
                                    <span class="info-box-number">{{ $monthlyTotal['total_laptop'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('laptops.index', ['status' => 'Dipinjam User', 'created_at_start' => $periodeAwal, 'created_at_end' => $periodeAkhir]) }}" style="text-decoration:none; color:inherit;">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-user"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Dipinjam</span>
                                    <span class="info-box-number">{{ $monthlyTotal['dipinjam'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('laptops.index', ['kondisi_akhir' => 'Rusak Ringan', 'created_at_start' => $periodeAwal, 'created_at_end' => $periodeAkhir]) }}" style="text-decoration:none; color:inherit;">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-tools"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rusak Ringan</span>
                                    <span class="info-box-number">{{ $monthlyTotal['rusak_ringan'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('laptops.index', ['kondisi_akhir' => 'Tidak Bisa Diperbaiki', 'created_at_start' => $periodeAwal, 'created_at_end' => $periodeAkhir]) }}" style="text-decoration:none; color:inherit;">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-times-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Tidak Bisa Diperbaiki</span>
                                    <span class="info-box-number">{{ $monthlyTotal['tidak_bisa_diperbaiki'] }}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Bulan Ini</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="monthly-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Total Laptop</th>
                            <th>Dipinjam</th>
                            <th>Rusak Ringan</th>
                            <th>Tidak Bisa Diperbaiki</th>
                            <th>Jumlah Perbaikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $periodeAwal = isset($monthlyData[0]) ? \Carbon\Carbon::parse(explode(' - ', $monthlyData[0]['tanggal'])[0]) : null;
                            $periodeAkhir = isset($monthlyData[count($monthlyData)-1]) ? \Carbon\Carbon::parse(explode(' - ', $monthlyData[count($monthlyData)-1]['tanggal'])[1]) : null;
                        @endphp
                        <tr>
                            <td>
                                @if($periodeAwal && $periodeAkhir)
                                    {{ $periodeAwal->translatedFormat('d F Y') }} - {{ $periodeAkhir->translatedFormat('d F Y') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $monthlyTotal['total_laptop'] }}</td>
                            <td>{{ $monthlyTotal['dipinjam'] }}</td>
                            <td>{{ $monthlyTotal['rusak_ringan'] }}</td>
                            <td>{{ $monthlyTotal['tidak_bisa_diperbaiki'] }}</td>
                            <td>{{ $monthlyTotal['jumlah_perbaikan'] ?? ($monthlyTotal['perbaikan_history']->count() ?? 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histori Perbaikan Bulan Ini</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered" id="repair-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Laptop</th>
                            <th>Nama Client</th>
                            <th>Nama Sparepart</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monthlyTotal['perbaikan_history'] as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->laptop->merk }} {{ $history->laptop->model }}</td>
                                <td>{{ $history->laptop->nama_client ?? '-' }}</td>
                                <td>{{ $history->nama_sparepart }}</td>
                                <td>Rp {{ number_format($history->harga, 0, ',', '.') }}</td>
                                <td>{{ $history->deskripsi }}</td>
                                <td>{{ $history->created_at->translatedFormat('d F Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data perbaikan bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        $('#monthly-table').DataTable();
        $('#repair-table').DataTable();
    });

    // Chart
    const ctx = document.getElementById('statistikBulanan');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(function($data) { 
                $dates = explode(' - ', $data['tanggal']);
                return \Carbon\Carbon::parse($dates[0])->translatedFormat('d F Y') . ' - ' . \Carbon\Carbon::parse($dates[1])->translatedFormat('d F Y');
            }, $monthlyData)) !!},
            datasets: [{
                label: 'Total Laptop',
                data: {!! json_encode(array_map(function($data) { 
                    return $data['total_laptop'];
                }, $monthlyData)) !!},
                borderColor: 'rgba(60, 141, 188, 1)',
                backgroundColor: 'rgba(60, 141, 188, 0.15)',
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(60, 141, 188, 1)',
                pointBorderWidth: 3
            }, {
                label: 'Dipinjam',
                data: {!! json_encode(array_map(function($data) { 
                    return $data['dipinjam'];
                }, $monthlyData)) !!},
                borderColor: 'rgba(40, 167, 69, 1)',
                backgroundColor: 'rgba(40, 167, 69, 0.15)',
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(40, 167, 69, 1)',
                pointBorderWidth: 3
            }, {
                label: 'Rusak Ringan',
                data: {!! json_encode(array_map(function($data) { 
                    return $data['rusak_ringan'];
                }, $monthlyData)) !!},
                borderColor: 'rgba(255, 193, 7, 1)',
                backgroundColor: 'rgba(255, 193, 7, 0.15)',
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(255, 193, 7, 1)',
                pointBorderWidth: 3
            }, {
                label: 'Tidak Bisa Diperbaiki',
                data: {!! json_encode(array_map(function($data) { 
                    return $data['tidak_bisa_diperbaiki'];
                }, $monthlyData)) !!},
                borderColor: 'rgba(220, 53, 69, 1)',
                backgroundColor: 'rgba(220, 53, 69, 0.15)',
                fill: true,
                tension: 0.45,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(220, 53, 69, 1)',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 14, family: 'Poppins, Arial, sans-serif' },
                        color: '#333',
                        padding: 18
                    }
                },
                tooltip: {
                    backgroundColor: '#fff',
                    titleColor: '#1976d2',
                    bodyColor: '#333',
                    borderColor: '#e3f0fa',
                    borderWidth: 1.5,
                    padding: 12,
                    titleFont: { weight: 'bold', size: 15 },
                    bodyFont: { size: 14 }
                },
                title: {
                    display: true,
                    text: 'Statistik Laptop Bulanan',
                    font: { size: 18, weight: 'bold', family: 'Poppins, Arial, sans-serif' },
                    color: '#1976d2',
                    padding: { bottom: 10 }
                }
            },
            layout: {
                padding: 10
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: { size: 14, weight: 'bold' },
                        color: '#1976d2',
                        padding: { right: 10 }
                    },
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        font: { size: 13 },
                        color: '#333'
                    },
                    suggestedMin: 0,
                    suggestedMax: 10,
                    grid: {
                        color: '#e3f0fa'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Minggu',
                        font: { size: 14, weight: 'bold' },
                        color: '#1976d2',
                        padding: { top: 10 }
                    },
                    ticks: {
                        font: { size: 13 },
                        color: '#333'
                    },
                    grid: {
                        color: '#e3f0fa'
                    }
                }
            },
            elements: {
                point: {
                    borderWidth: 3
                },
                line: {
                    borderWidth: 4
                }
            }
        }
    });
</script>
@stop