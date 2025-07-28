@extends('adminlte::page')

@section('title', 'Laporan Harian')

@section('content_header')
    <h1>Laporan Harian</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Statistik Harian</h3>
                <div class="card-tools">
                    <a href="{{ route('laporan.download.excel', ['type' => 'harian']) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                    <a href="{{ route('laporan.download.pdf', ['type' => 'harian']) }}" class="btn btn-danger btn-sm">
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
                        Grafik di bawah ini menampilkan statistik harian jumlah laptop, status peminjaman, kerusakan ringan, dan yang tidak bisa diperbaiki. Data diambil secara real-time setiap hari.
                    </div>
                </div>
                <div style="width: 100%; max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #f8f9fa 60%, #e3f0fa 100%); border-radius: 18px; box-shadow: 0 4px 24px rgba(25, 118, 210, 0.08); padding: 24px 18px 18px 18px; border: 1.5px solid #e3f0fa;">
                    <canvas id="statistikHarian" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histori Perbaikan Hari Ini</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Client</th>
                            <th>Nama Sparepart</th>
                            <th>Harga</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['perbaikan_history'] as $index => $perbaikan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->translatedFormat('d F Y') }}</td>
                            <td>{{ $perbaikan->laptop->nama_client ?? '-' }}</td>
                            <td>{{ $perbaikan->nama_sparepart }}</td>
                            <td>Rp {{ number_format($perbaikan->harga, 0, ',', '.') }}</td>
                            <td>{{ $perbaikan->deskripsi }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data perbaikan hari ini</td>
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
    const ctx = document.getElementById('statistikHarian');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['00:00', '06:00', '12:00', '18:00', '23:59'],
            datasets: [{
                label: 'Total Laptop',
                data: [
                    {{ $data['total_laptop'] }},
                    {{ $data['total_laptop'] }},
                    {{ $data['total_laptop'] }},
                    {{ $data['total_laptop'] }},
                    {{ $data['total_laptop'] }}
                ],
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
                data: [
                    {{ $data['dipinjam'] }},
                    {{ $data['dipinjam'] }},
                    {{ $data['dipinjam'] }},
                    {{ $data['dipinjam'] }},
                    {{ $data['dipinjam'] }}
                ],
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
                data: [
                    {{ $data['rusak_ringan'] }},
                    {{ $data['rusak_ringan'] }},
                    {{ $data['rusak_ringan'] }},
                    {{ $data['rusak_ringan'] }},
                    {{ $data['rusak_ringan'] }}
                ],
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
                data: [
                    {{ $data['tidak_bisa_diperbaiki'] }},
                    {{ $data['tidak_bisa_diperbaiki'] }},
                    {{ $data['tidak_bisa_diperbaiki'] }},
                    {{ $data['tidak_bisa_diperbaiki'] }},
                    {{ $data['tidak_bisa_diperbaiki'] }}
                ],
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
                    text: 'Statistik Laptop Harian',
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
                        text: 'Waktu',
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