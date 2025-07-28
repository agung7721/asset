@extends('layouts.app')

@section('page_title', 'Manajemen Laptop')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Laptop</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tambah-laptop">
                Tambah Laptop
            </button>
        </div>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Merk</th>
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Nama Client</th>
                    <th>Posisi Terakhir</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($laptops as $laptop)
                <tr>
                    <td>{{ $laptop->merk }}</td>
                    <td>{{ $laptop->model }}</td>
                    <td>{{ $laptop->serial_number }}</td>
                    <td>{{ $laptop->nama_client }}</td>
                    <td>{{ $laptop->posisi_terakhir }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="showDetail({{ $laptop->id }})">
                            Detail
                        </button>
                        <button class="btn btn-sm btn-warning" onclick="editLaptop({{ $laptop->id }})">
                            Edit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $laptops->links() }}
    </div>
</div>

@include('laptops.modals.create')
@include('laptops.modals.edit')
@include('laptops.modals.detail')
@endsection
