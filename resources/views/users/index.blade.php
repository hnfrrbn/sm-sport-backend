@extends('layouts.app')

@section('title', 'Daftar Pengguna')

@section('content')
<div class="container-fluid">
    <div class="modern-shell">
        <div class="row align-items-center justify-content-between mb-4">
            <div class="col-lg-8">
                <div class="hero-badge">Manajemen Pengguna</div>
                <h1 class="display-6 fw-bolder mb-1">Kelola akun pelanggan dan admin dengan lebih rapi</h1>
                <p class="text-muted mb-0">Lihat status akun, role, dan tindakan administratif dari satu halaman yang lebih terstruktur.</p>
            </div>
        </div>

        <div class="glass-card">
            <div class="section-header-row">
                <h3 class="mb-0"><i class="bi bi-people"></i> Daftar Pengguna</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle users-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>No. Telepon</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                                <td><strong>{{ $user->name }}</strong></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone ?? '-' }}</td>
                                <td>
                                    <span class="status-badge role-badge {{ $user->role === 'admin' ? 'status-cancelled' : 'status-confirmed' }}" style="color: #0f172a !important;">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-text-badge {{ $user->status === 'active' ? 'status-confirmed' : 'status-pending' }}" style="color: #0f172a !important;">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->locale('id')->translatedFormat('H.i d F Y') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus pengguna ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
