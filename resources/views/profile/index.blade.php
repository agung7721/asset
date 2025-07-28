@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <h1 class="mb-2">Profil Saya</h1>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg border-0 mx-auto" style="max-width: 420px; background: linear-gradient(135deg, #f8fafc 60%, #e0e7ff 100%); border-radius: 1.2rem;">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    @php
                        $user = auth()->user();
                        $avatar = asset('vendor/adminlte/dist/img/user2-160x160.jpg');
                        $initial = strtoupper(substr($user->name, 0, 1));
                    @endphp
                    <div style="width:110px;height:110px;margin:0 auto;display:flex;align-items:center;justify-content:center;background:#6366f1;color:white;font-size:3rem;border-radius:50%;box-shadow:0 2px 8px #6366f133;overflow:hidden;">
                        <img src="{{ $avatar }}" alt="User profile picture" onerror="this.style.display='none'" style="width:100%;height:100%;object-fit:cover;">
                        <span style="position:absolute;">{{ $initial }}</span>
                    </div>
                    <h2 class="profile-username text-center mt-3 mb-1" style="font-weight:700;">{{ $user->name }}</h2>
                    <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'info' }} px-3 py-2" style="font-size:1rem;">{{ ucfirst($user->role) }}</span>
                    <div class="mt-2">
                        <span class="badge badge-success" style="font-size:0.95rem;">Aktif</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-2 mb-md-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-envelope fa-lg mr-2 text-primary"></i>
                            <div>
                                <div class="font-weight-bold">Email</div>
                                <div class="text-muted">{{ $user->email }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-alt fa-lg mr-2 text-primary"></i>
                            <div>
                                <div class="font-weight-bold">Bergabung</div>
                                <div class="text-muted">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-shield-alt fa-lg mr-2 text-primary"></i>
                        <div class="font-weight-bold">Hak Akses</div>
                    </div>
                    <div class="text-muted ml-4">
                        @if($user->role === 'admin')
                            Create, Read, Update, Delete
                        @else
                            Create, Read
                        @endif
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6 mb-2 mb-md-0">
                        <button type="button" class="btn btn-outline-warning btn-block py-2 font-weight-bold" 
                                data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-key mr-1"></i> Ubah Password
                        </button>
                    </div>
                    @if($user->role === 'admin')
                    <div class="col-md-6">
                        <button type="button" class="btn btn-outline-danger btn-block py-2 font-weight-bold" 
                                data-toggle="modal" data-target="#deleteAccountModal">
                            <i class="fas fa-trash mr-1"></i> Hapus Akun
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Akun -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" role="dialog" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Hapus Akun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus akun ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="form-group">
                        <label for="password_confirmation">Masukkan Password Untuk Konfirmasi</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
