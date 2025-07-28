@extends('adminlte::page')

@section('title', 'Laporan Mingguan')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Laporan Mingguan</h1>
        </div>
        <div class="col-sm-6">
            <div class="float-right">
                <form action="{{ route('reports.weekly') }}" method="GET" class="form-inline">
                    <div class="input-group mr-2">
                        <input type="date" name="tanggal" class="form-control" 
                               value="{{ request('tanggal', now()->format('Y-m-d')) }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    Statistik Mingguan 
                    <small class="text-muted">
                        ({{ \Carbon\Carbon::parse(request('tanggal', now()))->startOfWeek()->format('d/m/Y') }} - 
                         {{ \Carbon\Carbon::parse(request('tanggal', now()))->endOfWeek()->format('d/m/Y') }})
                    </small>
                </h3>
                <div class="card-tools">
                    <a href="{{ route('reports.download.excel', [
                        'period' => 'weekly',
                        'tanggal' => request('tanggal', now()->format('Y-m-d'))
                    ]) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                    <a href="{{ route('reports.download.pdf', [
                        'period' => 'weekly',
                        'tanggal' => request('tanggal', now()->format('Y-m-d'))
                    ]) }}" class="btn btn-danger btn-sm">
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
                        Grafik di bawah ini menampilkan statistik mingguan jumlah laptop, status peminjaman, kerusakan ringan, dan yang tidak bisa diperbaiki. Data diambil per hari dalam satu minggu.
                    </div>
                </div>
                <div style="width: 100%; max-width: 600px; margin: 0 auto; background: linear-gradient(135deg, #f8f9fa 60%, #e3f0fa 100%); border-radius: 18px; box-shadow: 0 4px 24px rgba(25, 118, 210, 0.08); padding: 24px 18px 18px 18px; border: 1.5px solid #e3f0fa;">
                    <canvas id="statistikMingguan" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Harian</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped" id="detail-table">
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
                        @foreach($dailyData as $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('d F Y') }}</td>
                                <td>{{ $data['total_laptop'] }}</td>
                                <td>{{ $data['dipinjam'] }}</td>
                                <td>{{ $data['rusak_ringan'] }}</td>
                                <td>{{ $data['tidak_bisa_diperbaiki'] }}</td>
                                <td>{{ $data['jumlah_perbaikan'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Histori Perbaikan Minggu Ini</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="history-table">
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
                        @forelse($weeklyTotal['perbaikan_history'] as $index => $history)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $history->merk ?? ($history->laptop->merk ?? '-') }} {{ $history->model ?? ($history->laptop->model ?? '') }}</td>
                                <td>{{ $history->laptop->nama_client ?? '-' }}</td>
                                <td>{{ $history->nama_sparepart }}</td>
                                <td>Rp {{ number_format($history->harga, 0, ',', '.') }}</td>
                                <td>{{ $history->deskripsi }}</td>
                                <td>{{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data perbaikan minggu ini</td>
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
        // Inisialisasi DataTables
        $('#detail-table').DataTable({
            "paging": false,
            "searching": false,
            "ordering": false,
            "info": false
        });
        
        $('#history-table').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });

    // Chart
    const ctx = document.getElementById('statistikMingguan');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(function($data) { 
                return \Carbon\Carbon::parse($data['tanggal'])->format('d/m/Y');
            }, $dailyData)) !!},
            datasets: [{
                label: 'Total Laptop',
                data: {!! json_encode(array_map(function($data) { 
                    return $data['total_laptop'];
                }, $dailyData)) !!},
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
                }, $dailyData)) !!},
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
                }, $dailyData)) !!},
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
                }, $dailyData)) !!},
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
                    text: 'Statistik Laptop Mingguan',
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
                        text: 'Tanggal',
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
