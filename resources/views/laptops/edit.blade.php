@extends('adminlte::page')

@section('title', 'Edit Laptop')

@section('content_header')
    <h1>Edit Laptop</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('laptops.update', $laptop->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nomor_asset">Nomor Asset <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomor_asset" name="nomor_asset" 
                                    value="{{ old('nomor_asset', $laptop->nomor_asset) }}" readonly>
                                <small class="form-text text-muted">Format: KHZ-ASSET-LTP-YYYY-MM-DD-XXXX (otomatis)</small>
                            </div>

                            <div class="form-group">
                                <label for="merk">Merk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                                    id="merk" name="merk" value="{{ old('merk', $laptop->merk) }}" required>
                                @error('merk')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="model">Model <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                    id="model" name="model" value="{{ old('model', $laptop->model) }}" required>
                                @error('model')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="serial_number">Serial Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                    id="serial_number" name="serial_number" value="{{ old('serial_number', $laptop->serial_number) }}" required>
                                @error('serial_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kapasitas_ssd">Kapasitas SSD <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('kapasitas_ssd') is-invalid @enderror" 
                                        id="kapasitas_ssd" name="kapasitas_ssd" value="{{ old('kapasitas_ssd', $laptop->kapasitas_ssd) }}" 
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
                                        id="kapasitas_ram" name="kapasitas_ram" value="{{ old('kapasitas_ram', $laptop->kapasitas_ram) }}" 
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
                                    value="{{ old('tanggal_pembelian', $laptop->tanggal_pembelian) }}">
                                @error('tanggal_pembelian')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nama_client">Nama Client</label>
                                <input type="text" class="form-control @error('nama_client') is-invalid @enderror" 
                                    id="nama_client" name="nama_client" value="{{ old('nama_client', $laptop->nama_client) }}">
                                @error('nama_client')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="divisi">Divisi</label>
                                <input type="text" class="form-control @error('divisi') is-invalid @enderror" 
                                    id="divisi" name="divisi" value="{{ old('divisi', $laptop->divisi) }}"
                                    placeholder="Contoh: IT, HR, Finance">
                                @error('divisi')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_penyerahan">Tanggal Penyerahan</label>
                                <input type="date" class="form-control @error('tanggal_penyerahan') is-invalid @enderror" 
                                    id="tanggal_penyerahan" name="tanggal_penyerahan" 
                                    value="{{ old('tanggal_penyerahan', $laptop->tanggal_penyerahan) }}">
                                @error('tanggal_penyerahan')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="kondisi_awal">Kondisi Awal <span class="text-danger">*</span></label>
                                <select class="form-control @error('kondisi_awal') is-invalid @enderror" 
                                    id="kondisi_awal" name="kondisi_awal" required>
                                    <option value="">-- Pilih Kondisi --</option>
                                    <option value="Baru" {{ old('kondisi_awal', $laptop->kondisi_awal) == 'Baru' ? 'selected' : '' }}>Baru</option>
                                    <option value="Baik" {{ old('kondisi_awal', $laptop->kondisi_awal) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_awal', $laptop->kondisi_awal) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Tidak bisa diperbaiki" {{ old('kondisi_awal', $laptop->kondisi_awal) == 'Tidak bisa diperbaiki' ? 'selected' : '' }}>Tidak bisa diperbaiki</option>
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
                                    <option value="Baik" {{ old('kondisi_akhir', $laptop->kondisi_akhir) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                    <option value="Rusak Ringan" {{ old('kondisi_akhir', $laptop->kondisi_akhir) == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                                    <option value="Rusak Berat" {{ old('kondisi_akhir', $laptop->kondisi_akhir) == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                                </select>
                                @error('kondisi_akhir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="posisi_terakhir">Posisi Terakhir <span class="text-danger">*</span></label>
                                <select class="form-control @error('posisi_terakhir') is-invalid @enderror" 
                                    id="posisi_terakhir" name="posisi_terakhir" required>
                                    <option value="Dipinjam User" {{ old('posisi_terakhir', $laptop->posisi_terakhir) == 'Dipinjam User' ? 'selected' : '' }}>Dipinjam User</option>
                                    <option value="Perbaikan" {{ old('posisi_terakhir', $laptop->posisi_terakhir) == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                                    <option value="Gudang IT" {{ old('posisi_terakhir', $laptop->posisi_terakhir) == 'Gudang IT' ? 'selected' : '' }}>Gudang IT</option>
                                </select>
                                @error('posisi_terakhir')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanda_terima">Tanda Terima</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('tanda_terima') is-invalid @enderror" 
                                        id="tanda_terima" name="tanda_terima">
                                    <label class="custom-file-label" for="tanda_terima">Pilih file</label>
                                    @error('tanda_terima')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Format: jpeg, png, jpg. Maksimal 2MB</small>
                                @if($laptop->images->where('type', 'tanda_terima')->first())
                                    <div class="mt-2">
                                        <img src="{{ Storage::url($laptop->images->where('type', 'tanda_terima')->first()->image_path) }}" 
                                             class="img-thumbnail" style="max-height: 100px" alt="Tanda Terima">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Tambahkan hidden textarea untuk keterangan rusak -->
                    <textarea class="form-control d-none" 
                        id="keterangan_rusak" 
                        name="keterangan_rusak">{{ old('keterangan_rusak', $laptop->keterangan_rusak) }}</textarea>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('laptops.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary float-right">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal tetap di luar form -->
<div class="modal fade" id="modalKeteranganRusakBerat" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keterangan Rusak Berat</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="modal_keterangan_rusak">Keterangan Rusak Berat <span class="text-danger">*</span></label>
                    <textarea class="form-control" 
                        id="modal_keterangan_rusak" 
                        rows="3">{{ old('keterangan_rusak', $laptop->keterangan_rusak) }}</textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="simpanKeterangan">Simpan</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .custom-file-input:lang(en)~.custom-file-label::after {
        content: "Browse";
    }
</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Nama file akan muncul di label setelah dipilih
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    // Hapus atau modifikasi event handler kondisi_awal
    $('#kondisi_awal').on('change', function() {
        var kondisiAwal = $(this).val();
        var kondisiAkhirSelect = $('#kondisi_akhir');
        
        // Hanya disable jika kondisi awal "Tidak bisa diperbaiki"
        if (kondisiAwal === 'Tidak bisa diperbaiki') {
            kondisiAkhirSelect.val('Rusak Berat').prop('disabled', true);
        } else {
            kondisiAkhirSelect.prop('disabled', false);
        }
    });

    // Modifikasi validasi form
    $('form').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi form
        let requiredFields = ['merk', 'model', 'serial_number', 'kondisi_awal', 'posisi_terakhir'];
        let isValid = true;
        let emptyFields = [];

        requiredFields.forEach(field => {
            if (!$(`#${field}`).val()) {
                isValid = false;
                emptyFields.push($(`label[for="${field}"]`).text());
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

        // Enable kembali select yang disabled sebelum submit
        $('#kondisi_akhir').prop('disabled', false);

        // Submit form jika valid
        this.submit();
    });

    // Debug: Log nilai awal
    console.log('Kondisi Akhir:', '{{ $laptop->kondisi_akhir }}');

    // Monitor perubahan
    $('#kondisi_akhir').on('change', function() {
        console.log('Nilai baru:', $(this).val());
    });

    // Sebelum submit
    $('form').on('submit', function(e) {
        console.log('Form disubmit, nilai kondisi_akhir:', $('#kondisi_akhir').val());
    });

    // Tampilkan modal saat kondisi akhir dipilih "Rusak Berat"
    $('#kondisi_akhir').on('change', function() {
        if ($(this).val() === 'Rusak Berat') {
            $('#modalKeteranganRusakBerat').modal('show');
        } else {
            // Kosongkan keterangan jika kondisi bukan Rusak Berat
            $('#keterangan_rusak').val('');
        }
    });

    // Simpan keterangan saat tombol simpan diklik
    $('#simpanKeterangan').on('click', function() {
        var keterangan = $('#modal_keterangan_rusak').val();
        if (keterangan) {
            // Simpan keterangan ke textarea yang ada di form utama
            $('#keterangan_rusak').val(keterangan);
            $('#modalKeteranganRusakBerat').modal('hide');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Keterangan harus diisi!'
            });
        }
    });

    // Validasi sebelum submit
    $('form').on('submit', function(e) {
        if ($('#kondisi_akhir').val() === 'Rusak Berat') {
            var keterangan = $('#keterangan_rusak').val();
            if (!keterangan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Keterangan Rusak Berat harus diisi!'
                });
                $('#modalKeteranganRusakBerat').modal('show');
                return false;
            }
        }
    });

    // Jika kondisi awal sudah Rusak Berat, tampilkan modal saat halaman dimuat
    if ($('#kondisi_akhir').val() === 'Rusak Berat' && !$('#keterangan_rusak').val()) {
        $('#modalKeteranganRusakBerat').modal('show');
    }
});
</script>
@stop