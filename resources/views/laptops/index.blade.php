@extends('adminlte::page')

@section('title', 'Daftar Laptop')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Daftar Laptop</h1>
        </div>
        <div class="col-sm-6">
            <a href="{{ route('laptops.create') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Tambah Laptop
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-3 mb-2 offset-md-9">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari...">
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover" id="laptops-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Asset</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Kapasitas SSD</th>
                        <th>Kapasitas RAM</th>
                        <th>Serial Number</th>
                        <th>Client</th>
                        <th>Status</th>
                        <th>Kondisi Awal</th>
                        <th>Kondisi Akhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laptops as $laptop)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $laptop->nomor_asset }}</td>
                            <td>{{ $laptop->merk }}</td>
                            <td>{{ $laptop->model }}</td>
                            <td>{{ $laptop->kapasitas_ssd }} GB</td>
                            <td>{{ $laptop->kapasitas_ram }} GB</td>
                            <td>{{ $laptop->serial_number }}</td>
                            <td>{{ $laptop->nama_client ?? '-' }}</td>
                            <td>
                                @if($laptop->posisi_terakhir == 'Dipinjam User')
                                    <span class="badge badge-success">{{ $laptop->posisi_terakhir }}</span>
                                @elseif($laptop->posisi_terakhir == 'Perbaikan')
                                    <span class="badge badge-warning">{{ $laptop->posisi_terakhir }}</span>
                                @else
                                    <span class="badge badge-info">{{ $laptop->posisi_terakhir }}</span>
                                @endif
                            </td>
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
                            <td>
                                @if($laptop->kondisi_akhir)
                                    <span class="badge badge-{{ 
                                        $laptop->kondisi_akhir == 'Baik' ? 'success' : 
                                        ($laptop->kondisi_akhir == 'Rusak Ringan' ? 'warning' : 
                                        ($laptop->kondisi_akhir == 'Rusak Berat' ? 'danger' : 'secondary')) 
                                    }}">
                                        {{ $laptop->kondisi_akhir }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('laptops.show', $laptop->id) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('laptops.edit', $laptop->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="form-hapus-{{ $laptop->id }}" action="{{ route('laptops.destroy', $laptop->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus" data-id="{{ $laptop->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <div class="row">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" role="status">
                        Menampilkan {{ $laptops->firstItem() ?? 0 }} sampai {{ $laptops->lastItem() ?? 0 }} dari {{ $laptops->total() }} data
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="dataTables_paginate paging_simple_numbers float-right">
                        {{ $laptops->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .pagination {
            margin: 0;
            border-radius: 4px;
        }
        
        .page-item.active .page-link {
            background-color: #007bff;
            border-color: #007bff;
        }
        
        .page-link {
            padding: 0.5rem 0.75rem;
            color: #007bff;
            background-color: #fff;
            border: 1px solid #dee2e6;
        }
        
        .page-link:hover {
            color: #0056b3;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        .dataTables_info {
            padding-top: 0.5rem;
            color: #6c757d;
        }
        
        /* Tambahan style untuk tabel */
        .table th {
            vertical-align: middle;
            text-align: center;
            background-color: #f4f6f9;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .table td:nth-child(5),
        .table td:nth-child(6) {
            text-align: center;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,.075);
        }
        
        #searchInput {
            border-radius: 4px;
            border: 1px solid #ced4da;
        }
        
        #searchInput:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
    </style>
@stop

@section('js')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('.btn-hapus').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        let form = $('#form-hapus-' + id);
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Data?',
            text: 'Data akan dihapus permanen!',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn btn-danger mx-2',
                cancelButton: 'btn btn-primary'
            },
            buttonsStyling: false
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
                            text: 'Data berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Hapus baris tabel tanpa reload
                        form.closest('tr').fadeOut(500, function() { $(this).remove(); });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data',
                            showConfirmButton: true
                        });
                    }
                });
            }
        });
    });

    function filterTable() {
        const searchValue = $("#searchInput").val().toLowerCase();
        let url = new URL(window.location.href);
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        window.location.href = url.toString();
    }
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        $("#searchInput").val(urlParams.get('search'));
    }
    $("#searchInput").on("keyup", function(e) {
        const value = $(this).val();
        if ((value.length >= 3 && e.key === "Enter") || (value.length === 0 && e.key === "Enter")) {
            filterTable();
        }
    });
});
</script>
@stop
