@php
use App\Models\Laptop;

// Ubah perhitungan laptop dalam perbaikan
$laptopPerbaikan = Laptop::where('posisi_terakhir', 'Perbaikan')->count();

// Ubah perhitungan laptop rusak berat
$laptopRusak = Laptop::where('kondisi_akhir', 'Rusak Berat')->count();
@endphp

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <!-- Total Laptop -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalLaptop }}</h3>
                <p>Total Laptop</p>
            </div>
            <div class="icon">
                <i class="fas fa-laptop"></i>
            </div>
            <a href="{{ route('laptops.index') }}" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Laptop Dipinjam -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $laptopDipinjam }}</h3>
                <p>Laptop Dipinjam</p>
            </div>
            <div class="icon">
                <i class="fas fa-user"></i>
            </div>
            <a href="{{ route('laptops.index', ['status' => 'Dipinjam User']) }}" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Laptop Dalam Perbaikan -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $laptopPerbaikan }}</h3>
                <p>Laptop Dalam Perbaikan</p>
            </div>
            <div class="icon">
                <i class="fas fa-tools"></i>
            </div>
            <a href="{{ route('laptops.index', ['posisi_terakhir' => 'Perbaikan']) }}" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Laptop Tidak Bisa Diperbaiki -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $laptopRusak }}</h3>
                <p>Tidak Bisa Diperbaiki</p>
            </div>
            <div class="icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <a href="{{ route('laptops.index', ['kondisi_akhir' => 'Rusak Berat']) }}" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Gudang IT -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $laptopGudang }}</h3>
                <p>Gudang IT</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
            <a href="{{ route('laptops.index', ['status' => 'Gudang IT']) }}" class="small-box-footer">
                Selengkapnya <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>
@stop
