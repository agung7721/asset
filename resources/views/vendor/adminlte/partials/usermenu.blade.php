@php
    $user = auth()->user();
    $avatar = asset('vendor/adminlte/dist/img/user2-160x160.jpg');
    $initial = strtoupper(substr($user->name, 0, 1));
@endphp
<div class="dropdown-menu dropdown-menu-right p-3 shadow-lg border-0 usermenu-card" style="min-width: 320px; border-radius: 1.2rem; background: linear-gradient(135deg, #fff 80%, #e0e7ff 100%);">
    <div class="text-center mb-2">
        <div class="usermenu-avatar">
            <img src="{{ $avatar }}" alt="User profile picture" onerror="this.style.display='none'" style="width:100%;height:100%;object-fit:cover;">
            <span class="usermenu-initial">{{ $initial }}</span>
        </div>
        <div class="mt-2 usermenu-name">{{ $user->name }}</div>
        <span class="badge badge-{{ $user->role === 'admin' ? 'danger' : 'info' }} px-2 py-1 usermenu-role">{{ ucfirst($user->role) }}</span>
    </div>
    <div class="text-center text-muted mb-2 usermenu-email">{{ $user->email }}</div>
    <div class="text-center mb-2 usermenu-joined">
        <i class="fas fa-calendar-alt text-primary mr-1"></i>
        Bergabung: {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
    </div>
    <div class="dropdown-divider"></div>
    <a href="{{ route('profile.index') }}" class="dropdown-item d-flex align-items-center usermenu-link">
        <i class="fas fa-user-circle mr-2 text-primary"></i> Profil Saya
    </a>
    <a href="#" class="dropdown-item d-flex align-items-center usermenu-link" data-toggle="modal" data-target="#changePasswordModal">
        <i class="fas fa-key mr-2 text-warning"></i> Ubah Password
    </a>
    <form action="{{ route('logout') }}" method="POST" class="m-0 p-0" onsubmit="return confirm('Anda yakin ingin keluar?');">
        @csrf
        <button type="submit" class="dropdown-item d-flex align-items-center text-danger usermenu-logout-btn" style="font-weight:600;">
            <span class="logout-illustration mr-2"><i class="fas fa-power-off"></i></span> Keluar Akun
        </button>
    </form>
</div>
<style>
.usermenu-card {
    box-shadow: 0 8px 32px rgba(60,60,60,0.13)!important;
    padding: 36px 28px 24px 28px!important;
}
.usermenu-avatar {
    width:80px;height:80px;margin:0 auto;display:flex;align-items:center;justify-content:center;background:#6366f1;color:white;font-size:2.5rem;border-radius:50%;box-shadow:0 2px 8px #6366f133;overflow:hidden;position:relative;transition:box-shadow 0.2s;}
.usermenu-avatar:hover { box-shadow:0 4px 16px #6366f155; }
.usermenu-initial { position:absolute; left:0; right:0; text-align:center; font-weight:700; font-size:2.2rem; pointer-events:none; }
.usermenu-name { font-weight:700; font-size:1.2rem; letter-spacing:0.5px; }
.usermenu-role { font-size:0.95rem; border-radius:8px; margin-top:4px; }
.usermenu-email { font-size:1rem; }
.usermenu-joined { font-size:0.92rem; color:#6b7280; }
.usermenu-link { border-radius:8px; transition:background 0.18s; }
.usermenu-link:hover { background:#f3f4f6; }
.usermenu-logout-btn { border-radius:8px; transition:background 0.18s, color 0.18s; font-size:1.08rem; }
.usermenu-logout-btn:hover { background:#ffeaea; color:#b91c1c!important; }
.logout-illustration { font-size:1.4rem; color:#e74c3c; display:flex; align-items:center; }
</style> 