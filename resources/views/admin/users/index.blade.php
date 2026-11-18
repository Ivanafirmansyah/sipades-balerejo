@extends('layouts.panel')

@section('title', 'Manajemen Admin')
@section('subtitle', 'Kelola akun admin dan warga')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h6 class="fw-bold mb-0">Daftar Akun</h6>
            <a href="{{ route('admin.users.create') }}" class="btn btn-sm btn-hijau"><i class="bi bi-person-plus me-1"></i> Tambah Akun</a>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari nama / email...">
            </div>
            <div class="col-md-3">
                <select name="role" class="form-select form-select-sm">
                    <option value="">Semua Role</option>
                    <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                    <option value="user" @selected(request('role') === 'user')>Warga</option>
                </select>
            </div>
            <div class="col-md-2"><button class="btn btn-sm btn-hijau w-100">Filter</button></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Email</th><th>No. HP</th><th>Role</th><th>Dibuat</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="fw-semibold">{{ $user->name }} @if ($user->id === auth()->id())<span class="badge badge-soft-info">Anda</span>@endif</td>
                            <td class="small">{{ $user->email ?? '' }}@if ($user->nik)<div class="text-muted font-monospace"><i class="bi bi-person-vcard me-1"></i>{{ $user->nik }}</div>@endif</td>
                            <td class="small">{{ $user->phone ?? '-' }}</td>
                            <td>
                                <span class="badge badge-soft-{{ $user->role === 'admin' ? 'success' : 'secondary' }}">
                                    {{ $user->role === 'admin' ? 'Admin' : 'Warga' }}
                                </span>
                            </td>
                            <td class="small">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus akun {{ $user->name }}?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Tidak ada akun.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</div>
@endsection
