<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN BULANAN</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 10px; }
        .summary { margin-bottom: 20px; }
        .summary-title { font-weight: bold; margin-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
        .footer { margin-top: 30px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN BULANAN</h2>
        <div>Periode: {{ $data['start_date'] ? \Carbon\Carbon::parse($data['start_date'])->translatedFormat('d F Y') : '-' }} s/d {{ $data['end_date'] ? \Carbon\Carbon::parse($data['end_date'])->translatedFormat('d F Y') : '-' }}</div>
    </div>
    <div class="summary">
        <div class="summary-title">Rekap Bulanan</div>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Total Laptop</th>
                    <th>Dipinjam</th>
                    <th>Rusak Ringan</th>
                    <th>Tidak Bisa Diperbaiki</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['monthly_data'] ?? [] as $row)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($row['tanggal'])->translatedFormat('d F Y') }}</td>
                    <td>{{ $row['total_laptop'] }}</td>
                    <td>{{ $row['dipinjam'] }}</td>
                    <td>{{ $row['rusak_ringan'] }}</td>
                    <td>{{ $row['tidak_bisa_diperbaiki'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Tambahkan tabel histori perbaikan jika ada, dengan kolom Nama Client setelah Laptop -->
    @if(isset($data['monthlyTotal']['perbaikan_history']))
    <div class="summary-title">Histori Perbaikan Bulan Ini</div>
    <table>
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
            @forelse($data['monthlyTotal']['perbaikan_history'] ?? [] as $index => $history)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $history->laptop->merk ?? '-' }} {{ $history->laptop->model ?? '' }}</td>
                <td>{{ $history->laptop->nama_client ?? '-' }}</td>
                <td>{{ $history->nama_sparepart ?? '-' }}</td>
                <td>Rp {{ isset($history->harga) ? number_format($history->harga, 0, ',', '.') : '-' }}</td>
                <td>{{ $history->deskripsi ?? '-' }}</td>
                <td>{{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->translatedFormat('d F Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;">Tidak ada data perbaikan bulan ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
    <div class="footer">
        Dicetak oleh: {{ $user_name ?? '-' }}<br>
        Dicetak pada: {{ $printedAt->translatedFormat('d F Y') }}
    </div>
</body>
</html> 