@extends('adminlte::page')

@section('title', 'Tambah Riwayat Perbaikan')

@section('content_header')
    <h1>Tambah Riwayat Perbaikan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Laptop: {{ $laptop->merk }} - {{ $laptop->model }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('laptops.perbaikan.store', $laptop->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="tanggal_perbaikan">Tanggal Perbaikan <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_perbaikan') is-invalid @enderror" 
                            id="tanggal_perbaikan" name="tanggal_perbaikan" value="{{ old('tanggal_perbaikan') }}" required>
                        @error('tanggal_perbaikan')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="nama_sparepart">Nama Sparepart <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_sparepart') is-invalid @enderror" 
                            id="nama_sparepart" name="nama_sparepart" value="{{ old('nama_sparepart') }}" required>
                        @error('nama_sparepart')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                            id="harga" name="harga" value="{{ old('harga') }}" required>
                        @error('harga')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Perbaikan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                            id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <a href="{{ route('laptops.show', $laptop->id) }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary float-right">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
