@extends('adminlte::page')

@section('title', 'Detail Laptop')

@section('content_header')
    <h1>Detail Laptop</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 200px">Nomor Asset</th>
                                <td>{{ $laptop->nomor_asset ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Merk</th>
                                <td>{{ $laptop->merk ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $laptop->model ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Serial Number</th>
                                <td>{{ $laptop->serial_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kapasitas SSD</th>
                                <td>{{ $laptop->kapasitas_ssd ?? '-' }} GB</td>
                            </tr>
                            <tr>
                                <th>Kapasitas RAM</th>
                                <td>{{ $laptop->kapasitas_ram ?? '-' }} GB</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pembelian</th>
                                <td>{{ $laptop->tanggal_pembelian ? \Carbon\Carbon::parse($laptop->tanggal_pembelian)->translatedFormat('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nama Client</th>
                                <td>{{ $laptop->nama_client ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Divisi</th>
                                <td>{{ $laptop->divisi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Penyerahan</th>
                                <td>{{ $laptop->tanggal_penyerahan ? \Carbon\Carbon::parse($laptop->tanggal_penyerahan)->translatedFormat('d F Y') : '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi Awal</th>
                                <td>
                                    @if($laptop->kondisi_awal)
                                        <span class="badge badge-{{ 
                                            $laptop->kondisi_awal == 'Baru' ? 'success' : 
                                            ($laptop->kondisi_awal == 'Baik' ? 'info' : 
                                            ($laptop->kondisi_awal == 'Rusak Ringan' ? 'warning' : 'danger')) 
                                        }}">
                                            {{ $laptop->kondisi_awal }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Kondisi Terakhir</th>
                                <td>
                                    @if($laptop->kondisi_akhir)
                                        <span class="badge {{ $laptop->kondisi_akhir == 'Rusak Berat' ? 'badge-danger' : 'badge-info' }}">
                                            {{ $laptop->kondisi_akhir }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @if($laptop->kondisi_akhir == 'Rusak Berat' && $laptop->keterangan_rusak)
                            <tr>
                                <th>Keterangan Rusak Berat</th>
                                <td>
                                    <div class="text-danger">
                                        {{ $laptop->keterangan_rusak }}
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th>Posisi Terakhir</th>
                                <td>
                                    <span class="badge badge-{{ 
                                        $laptop->posisi_terakhir == 'Dipinjam User' ? 'primary' : 
                                        ($laptop->posisi_terakhir == 'Perbaikan' ? 'warning' : 'secondary') 
                                    }}">
                                        {{ $laptop->posisi_terakhir ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Tanda Terima</h3>
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTandaTerima">
                                    <i class="fas fa-plus"></i> Tambah Foto
                                </button>
                            </div>
                            <div class="card-body">
                                @if($laptop->tandaTerima && $laptop->tandaTerima->count() > 0)
                                    <div class="row">
                                        @foreach($laptop->tandaTerima as $foto)
                                            <div class="col-md-6 mb-3">
                                                <div class="position-relative">
                                                    <div class="foto-container" style="height: 200px; overflow: hidden;">
                                                        <img src="{{ Storage::url($foto->path) }}" 
                                                             class="img-fluid img-thumbnail foto-preview-trigger w-100 h-100" 
                                                             alt="Tanda Terima"
                                                             data-img="{{ Storage::url($foto->path) }}"
                                                             style="cursor: pointer; object-fit: cover;">
                                                    </div>
                                                    <div class="position-absolute" style="top: 5px; right: 5px;">
                                                        <!-- Tombol Download -->
                                                        <a href="{{ Storage::url($foto->path) }}" 
                                                           class="btn btn-success btn-sm mr-1" 
                                                           download="{{ $foto->nama_file }}">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <!-- Tombol Hapus -->
                                                        <form action="{{ route('laptops.tanda-terima.destroy', [$laptop->id, $foto->id]) }}" 
                                                              method="POST" 
                                                              class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <!-- Info file -->
                                                    <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.7); padding: 5px;">
                                                        <small class="text-white d-block">
                                                            {{ $foto->nama_file }} ({{ number_format($foto->ukuran / 1024, 1) }} KB)
                                                        </small>
                                                        <small class="text-white d-block">
                                                            Upload: {{ \Carbon\Carbon::parse($foto->created_at)->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">Tidak ada tanda terima</p>
                                @endif
                            </div>
                        </div>

                        @if($laptop->histories->count() > 0)
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Riwayat Laptop</h3>
                                </div>
                                <div class="card-body p-0">
                                    <div class="timeline timeline-inverse p-3">
                                        @foreach($laptop->histories()->latest()->get() as $history)
                                            <div class="time-label">
                                                <span class="bg-primary">
                                                    {{ \Carbon\Carbon::parse($history->tanggal)->format('d M Y') }}
                                                </span>
                                            </div>
                                            <div>
                                                <i class="fas fa-history bg-primary"></i>
                                                <div class="timeline-item">
                                                    <h3 class="timeline-header">{{ $history->keterangan }}</h3>
                                                    @if($history->image_path)
                                                        <div class="timeline-body">
                                                            <img src="{{ url('storage/app/public/' . $history->image_path) }}" 
                                                                 class="img-fluid" style="max-height: 200px" alt="History Image">
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card mt-4">
                            <div class="card-header">
                                <h3 class="card-title">Riwayat Perbaikan</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalPerbaikan">
                                        <i class="fas fa-plus"></i> Tambah Perbaikan
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Sparepart</th>
                                                <th>Harga</th>
                                                <th>Deskripsi</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($laptop->perbaikan as $perbaikan)
                                                <tr>
                                                    <td>{{ \Carbon\Carbon::parse($perbaikan->tanggal_perbaikan)->format('d/m/Y') }}</td>
                                                    <td>{{ $perbaikan->nama_sparepart ?? '-' }}</td>
                                                    <td>{{ $perbaikan->harga ? 'Rp ' . number_format($perbaikan->harga, 0, ',', '.') : '-' }}</td>
                                                    <td>{{ $perbaikan->deskripsi ?? '-' }}</td>
                                                    <td>
                                                        <form action="{{ route('laptops.perbaikan.destroy', [$laptop->id, $perbaikan->id]) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-perbaikan">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">Belum ada data perbaikan</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Perpindahan Client -->
                        <div class="card mt-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title">Riwayat Perpindahan Client</h3>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalPerpindahan">
                                    <i class="fas fa-exchange-alt"></i> Tambah Perpindahan
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Tanggal & Waktu</th>
                                                <th>Dari</th>
                                                <th>Ke</th>
                                                <th>Keterangan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($laptop->riwayat()->orderBy('tanggal_perpindahan', 'desc')->get() as $riwayat)
                                                <tr>
                                                    <td class="align-middle">
                                                        <strong>{{ \Carbon\Carbon::parse($riwayat->tanggal_perpindahan)->translatedFormat('dddd, D MMMM Y') }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ \Carbon\Carbon::parse($riwayat->tanggal_perpindahan)->format('H:i') }} WIB</small>
                                                    </td>
                                                    <td class="align-middle">
                                                        <strong>{{ $riwayat->client_lama ?? '-' }}</strong>
                                                        @if($riwayat->divisi_lama)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-building"></i> {{ $riwayat->divisi_lama }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        <strong>{{ $riwayat->client_baru ?? '-' }}</strong>
                                                        @if($riwayat->divisi_baru)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-building"></i> {{ $riwayat->divisi_baru }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle">
                                                        {{ $riwayat->keterangan ?? '-' }}
                                                    </td>
                                                    <td class="align-middle">
                                                        <form action="{{ route('laptops.riwayat.destroy', [$laptop->id, $riwayat->id]) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-perpindahan">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-3">
                                                        <i class="fas fa-info-circle text-info"></i> Belum ada riwayat perpindahan
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Kembali</a>
                        <a href="{{ route('laptops.edit', $laptop->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Foto -->
<div class="modal fade" id="modalTandaTerima" tabindex="-1" role="dialog" aria-labelledby="modalTandaTerimaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('laptops.tanda-terima.store', $laptop->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTandaTerimaLabel">Tambah Foto Tanda Terima</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanda_terima">Pilih Foto</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input @error('tanda_terima') is-invalid @enderror" 
                                id="tanda_terima" name="tanda_terima[]" multiple accept="image/*">
                            <label class="custom-file-label" for="tanda_terima">Pilih file</label>
                        </div>
                        <small class="form-text text-muted">Format: jpeg, png, jpg. Maksimal 25MB per file. Bisa pilih lebih dari 1 file.</small>
                        @error('tanda_terima')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div id="preview" class="row mt-3">
                        <!-- Preview foto akan muncul di sini -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Preview Foto -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Preview Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="previewImage" class="img-fluid" alt="Preview">
            </div>
        </div>
    </div>
</div>

<!-- Modal Perpindahan -->
<div class="modal fade" id="modalPerpindahan" tabindex="-1" role="dialog" aria-labelledby="modalPerpindahanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('laptops.riwayat.store', $laptop->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <!-- Client Lama (readonly) -->
                    <div class="form-group">
                        <label for="client_lama">Client Lama</label>
                        <input type="text" class="form-control" 
                            id="client_lama" name="client_lama" 
                            value="{{ $laptop->nama_client }}" readonly>
                    </div>

                    <!-- Divisi Lama (readonly) -->
                    <div class="form-group">
                        <label for="divisi_lama">Divisi Lama</label>
                        <input type="text" class="form-control" 
                            id="divisi_lama" name="divisi_lama" 
                            value="{{ $laptop->divisi }}" readonly>
                    </div>

                    <!-- Client Baru -->
                    <div class="form-group">
                        <label for="client_baru">Client Baru</label>
                        <input type="text" class="form-control @error('client_baru') is-invalid @enderror" 
                            id="client_baru" name="client_baru" value="{{ old('client_baru') }}">
                        @error('client_baru')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Divisi Baru -->
                    <div class="form-group">
                        <label for="divisi_baru">Divisi Baru</label>
                        <input type="text" class="form-control @error('divisi_baru') is-invalid @enderror" 
                            id="divisi_baru" name="divisi_baru" value="{{ old('divisi_baru') }}">
                        @error('divisi_baru')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Kondisi Terakhir -->
                    <div class="form-group">
                        <label for="kondisi_akhir">Kondisi Terakhir</label>
                        <select class="form-control @error('kondisi_akhir') is-invalid @enderror" 
                            id="kondisi_akhir" name="kondisi_akhir">
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak Ringan">Rusak Ringan</option>
                            <option value="Rusak Berat">Rusak Berat</option>
                        </select>
                        @error('kondisi_akhir')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tanggal Perpindahan -->
                    <div class="form-group">
                        <label for="tanggal_perpindahan">Tanggal & Waktu Perpindahan</label>
                        <input type="datetime-local" class="form-control @error('tanggal_perpindahan') is-invalid @enderror" 
                            id="tanggal_perpindahan" name="tanggal_perpindahan" 
                            value="{{ old('tanggal_perpindahan') }}">
                        @error('tanggal_perpindahan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                            id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Tambahkan Modal Perbaikan -->
<div class="modal fade" id="modalPerbaikan" tabindex="-1" role="dialog" aria-labelledby="modalPerbaikanLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('laptops.repair.store', $laptop->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPerbaikanLabel">Tambah Data Perbaikan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_perbaikan">Tanggal Perbaikan <span class="text-danger">*</span></label>
                        <input type="date" 
                               class="form-control @error('tanggal_perbaikan') is-invalid @enderror" 
                               id="tanggal_perbaikan" 
                               name="tanggal_perbaikan" 
                               value="{{ old('tanggal_perbaikan', date('Y-m-d')) }}"
                               required>
                        @error('tanggal_perbaikan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_sparepart">Nama Sparepart <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control @error('nama_sparepart') is-invalid @enderror" 
                               id="nama_sparepart" 
                               name="nama_sparepart" 
                               value="{{ old('nama_sparepart') }}"
                               required>
                        @error('nama_sparepart')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" 
                                   class="form-control @error('harga') is-invalid @enderror" 
                                   id="harga" 
                                   name="harga" 
                                   value="{{ old('harga') }}"
                                   required>
                        </div>
                        @error('harga')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Perbaikan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                  id="deskripsi" 
                                  name="deskripsi" 
                                  rows="3"
                                  required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Konfirmasi hapus dengan SweetAlert2
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        let form = $(this);
        
        Swal.fire({
            title: 'Hapus Foto?',
            text: "Foto akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Foto berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus foto',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    });

    // Handler untuk riwayat perbaikan dan perpindahan sama seperti di atas
    $('.delete-perbaikan').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        
        Swal.fire({
            title: 'Hapus Riwayat Perbaikan?',
            text: "Data perbaikan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    $('.delete-perpindahan').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form');
        
        Swal.fire({
            title: 'Hapus Riwayat Perpindahan?',
            text: "Data perpindahan akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Notifikasi setelah aksi
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            background: '#4CAF50',
            color: '#fff',
            iconColor: '#fff'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end',
            background: '#F44336',
            color: '#fff',
            iconColor: '#fff'
        });
    @endif

    // Preview foto yang dipilih untuk upload
    $("#tanda_terima").on("change", function() {
        var files = Array.from(this.files);
        var fileNames = files.map(file => file.name).join(', ');
        $(this).siblings(".custom-file-label").addClass("selected").html(fileNames);
        
        // Preview foto
        $("#preview").empty();
        files.forEach(function(file) {
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#preview").append(`
                        <div class="col-md-6 mb-2">
                            <img src="${e.target.result}" class="img-fluid" alt="Preview">
                        </div>
                    `);
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // Preview foto yang sudah diupload
    $('.foto-preview-trigger').click(function() {
        var imgSrc = $(this).attr('src');
        console.log('Preview imgSrc:', imgSrc);
        $('#previewImage').attr('src', imgSrc);
        $('#previewModal').modal('show');
    });

    // Reset form saat modal perpindahan ditutup
    $('#modalPerpindahan').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
    });

    // Tampilkan notifikasi setelah aksi perpindahan
    @if(session('perpindahan_success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('perpindahan_success') }}",
            showConfirmButton: false,
            timer: 2000,
            toast: true,
            position: 'top-end'
        });
    @endif

    // Tampilkan modal perbaikan hanya jika ada error perbaikan
    @if($errors->has('tanggal_perbaikan') || $errors->has('sparepart') || $errors->has('harga') || $errors->has('deskripsi'))
        $('#modalPerbaikan').modal('show');
    @endif
});
</script>
@endpush

@push('css')
<style>
    .timeline {
        margin: 0;
        padding: 0;
        position: relative;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: 2px;
    }
    .foto-preview-trigger:hover {
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }

    /* Styling untuk SweetAlert */
    .swal2-popup.swal2-toast {
        padding: 0.75rem 1.5rem;
        font-size: 0.875rem;
    }
    
    .swal2-popup.swal2-toast .swal2-title {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }
    
    .swal2-popup.swal2-toast .swal2-icon {
        margin: 0 0.5rem 0 0;
        font-size: 1.25rem;
    }

    .swal2-popup.swal2-toast .swal2-timer-progress-bar {
        background: rgba(255,255,255,0.3);
    }

    #previewImage {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
    }
</style>
@endpush

@stop
