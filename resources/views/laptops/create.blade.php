@extends('adminlte::page')

@section('title', 'Tambah Laptop')

@section('content_header')
    <h1>Tambah Data Laptop Baru</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Input Data Laptop</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('laptops.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Laptop</h4>
                            <div class="form-group">
                                <label for="nomor_asset">Nomor Asset <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_asset" name="nomor_asset" 
                                    value="{{ $nomorAsset }}" readonly>
                                <small class="form-text text-muted">Format: KHZ-ASSET-LTP-YYYY-MM-DD-XXXX (otomatis)</small>
                            </div>

                            <div class="form-group">
                                <label for="merk">Merk Laptop <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                                    id="merk" name="merk" value="{{ old('merk') }}" 
                                    placeholder="Contoh: Lenovo, HP, Dell" required>
                                @error('merk')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="model">Model Laptop <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                    id="model" name="model" value="{{ old('model') }}" 
                                    placeholder="Contoh: ThinkPad T480s" required>
                                @error('model')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                    id="serial_number" name="serial_number" value="{{ old('serial_number') }}" 
                                    placeholder="Masukkan nomor seri laptop" required>
                                @error('serial_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_ssd">Kapasitas SSD <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('kapasitas_ssd') is-invalid @enderror" 
                                        id="kapasitas_ssd" name="kapasitas_ssd" value="{{ old('kapasitas_ssd') }}" 
                                        placeholder="Contoh: 256" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">GB</span>
                                    </div>
                                </div>
                                @error('kapasitas_ssd')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_ram">Kapasitas RAM <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('kapasitas_ram') is-invalid @enderror" 
                                        id="kapasitas_ram" name="kapasitas_ram" value="{{ old('kapasitas_ram') }}" 
                                        placeholder="Contoh: 8" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text">GB</span>
                                    </div>
                                </div>
                                @error('kapasitas_ram')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_pembelian">Tanggal Pembelian</label>
                                <input type="date" class="form-control @error('tanggal_pembelian') is-invalid @enderror" 
                                    id="tanggal_pembelian" name="tanggal_pembelian" 
                                    value="{{ old('tanggal_pembelian') }}">
                                @error('tanggal_pembelian')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h4 class="mb-3">Informasi Pengguna</h4>
                            <div class="form-group">
                                <label for="nama_client">Nama Pengguna</label>
                                <input type="text" class="form-control @error('nama_client') is-invalid @enderror" 
                                    id="nama_client" name="nama_client" 
                                    value="{{ old('nama_client') }}"
                                    placeholder="Nama lengkap pengguna laptop">
                                @error('nama_client')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="divisi">Divisi/Departemen</label>
                                <input type="text" class="form-control @error('divisi') is-invalid @enderror" 
                                    id="divisi" name="divisi" 
                                    value="{{ old('divisi') }}"
                                    placeholder="Contoh: IT, HR, Finance">
                                @error('divisi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_penyerahan">Tanggal Penyerahan</label>
                                <input type="date" class="form-control @error('tanggal_penyerahan') is-invalid @enderror" 
                                    id="tanggal_penyerahan" name="tanggal_penyerahan" 
                                    value="{{ old('tanggal_penyerahan') }}">
                                @error('tanggal_penyerahan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kondisi_awal">Kondisi Awal <span class="text-danger">*</span></label>
                                <select class="form-control @error('kondisi_awal') is-invalid @enderror" 
                                    id="kondisi_awal" name="kondisi_awal" required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Baru" {{ old('kondisi_awal') == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('kondisi_awal') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_awal') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Tidak bisa diperbaiki" {{ old('kondisi_awal') == 'Tidak bisa diperbaiki' ? 'selected' : '' }}>Tidak bisa diperbaiki</option>
                                </select>
                                @error('kondisi_awal')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kondisi_akhir">Kondisi Terakhir</label>
                                <select class="form-control @error('kondisi_akhir') is-invalid @enderror" 
                                    id="kondisi_akhir" name="kondisi_akhir">
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Baik" {{ old('kondisi_akhir') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_akhir') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ old('kondisi_akhir') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi_akhir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="posisi_terakhir">Status Laptop <span class="text-danger">*</span></label>
                                <select class="form-control @error('posisi_terakhir') is-invalid @enderror" 
                                    id="posisi_terakhir" name="posisi_terakhir" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="Dipinjam User" {{ old('posisi_terakhir') == 'Dipinjam User' ? 'selected' : '' }}>Dipinjam User</option>
                                    <option value="Perbaikan" {{ old('posisi_terakhir') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                    <option value="Gudang IT" {{ old('posisi_terakhir') == 'Gudang IT' ? 'selected' : '' }}>Gudang IT</option>
                                </select>
                                @error('posisi_terakhir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="window.history.back()">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" class="btn btn-primary float-right">
                            <i class="fas fa-save"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card-title {
        font-weight: bold;
        font-size: 1.1rem;
    }
    h4 {
        color: #2c3e50;
        font-size: 1.2rem;
        font-weight: 600;
    }
    .form-group label {
        font-weight: 500;
    }
    .text-danger {
        font-weight: bold;
    }
    .required:after {
        content: '*';
        color: red;
        margin-left: 5px;
    }
</style>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Tampilkan notifikasi jika ada
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            toast: true,
            position: 'top-end'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            showConfirmButton: true
        });
    @endif

    // Validasi form sebelum submit
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        let requiredFields = ['merk', 'model', 'serial_number', 'kapasitas_ssd', 'kapasitas_ram', 'kondisi_awal', 'posisi_terakhir'];
        let isValid = true;
        let emptyFields = [];

        requiredFields.forEach(field => {
            if (!$(`#${field}`).val()) {
                isValid = false;
                $(`#${field}`).addClass('is-invalid');
                emptyFields.push($(`label[for="${field}"]`).text().replace(' *', ''));
            } else {
                $(`#${field}`).removeClass('is-invalid');
            }
        });

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Harap isi semua field yang wajib diisi!',
                footer: `Field yang kosong: ${emptyFields.join(', ')}`
            });
            return;
        }

        // Submit form jika valid
        this.submit();
    });
});
</script>
@stop
