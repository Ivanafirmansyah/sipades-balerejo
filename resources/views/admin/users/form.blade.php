@extends('layouts.panel')

@php($isEdit = $user->exists)
@section('title', $isEdit ? 'Edit Akun' : 'Tambah Akun')
@section('subtitle', $isEdit ? 'Perbarui data akun' : 'Buat akun admin atau warga baru')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<div class="card">
    <div class="card-body p-4">
        <form action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}" method="POST" class="row g-3">
            @csrf
            @if ($isEdit) @method('PUT') @endif

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Nama <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">No. HP</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Role <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    <option value="user" @selected(old('role', $user->role) === 'user')>Warga</option>
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold">Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '*' }}</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" @if(!$isEdit) required @endif minlength="8">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12 d-flex gap-2 mt-2">
                <button class="btn btn-hijau px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
