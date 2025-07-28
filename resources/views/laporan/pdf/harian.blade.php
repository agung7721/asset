<!DOCTYPE html>
<html>
<head>
    <title>LAPORAN HARIAN</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 10px; }
        .summary { margin-bottom: 20px; }
        .summary-title { font-weight: bold; margin-bottom: 8px; }
        .summary-list { margin-left: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
        .footer { margin-top: 30px; font-size: 13px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HARIAN</h2>
        <div>Periode: {{ $data['date'] ?? date('d/m/Y') }}</div>
    </div>
    <div class="summary">
        <div class="summary-title">Ringkasan</div>
        <div class="summary-list">
            <div>Total Laptop: {{ $data['total_laptop'] ?? '-' }}</div>
            <div>Dipinjam: {{ $data['dipinjam'] ?? '-' }}</div>
            <div>Rusak Ringan: {{ $data['rusak_ringan'] ?? '-' }}</div>
            <div>Tidak Bisa Diperbaiki: {{ $data['tidak_bisa_diperbaiki'] ?? '-' }}</div>
        </div>
    </div>
    <div>
        <div class="summary-title">Histori Perbaikan</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Laptop</th>
                    <th>Nama Client</th>
                    <th>Kerusakan</th>
                    <th>Tindakan</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data['perbaikan_history'] ?? [] as $index => $perbaikan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $perbaikan->laptop->merk ?? '-' }} {{ $perbaikan->laptop->model ?? '' }}</td>
                    <td>{{ $perbaikan->laptop->nama_client ?? '-' }}</td>
                    <td>{{ $perbaikan->nama_sparepart ?? '-' }}</td>
                    <td>{{ $perbaikan->deskripsi ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->translatedFormat('d F Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Tidak ada riwayat perbaikan pada periode ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="footer">
        Dicetak oleh: {{ $user_name ?? '-' }}<br>
        Dicetak pada: {{ $printedAt->format('d/m/Y') }}
    </div>
</body>
</html> 