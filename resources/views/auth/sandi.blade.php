@extends('layouts.auth')

@section('title', 'Kata Sandi')
@section('heading', $user->wajib_ganti_sandi ? 'Buat Kata Sandi Baru' : 'Ganti Kata Sandi')
@section('subheading', $user->wajib_ganti_sandi ? 'Langkah terakhir sebelum memakai layanan desa' : 'Perbarui kata sandi akun Anda')

@section('content')
<div class="akun-info mb-3">
    <span class="akun-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
    <div class="lh-sm">
        <div class="fw-bold">{{ $user->name }}</div>
        <div class="small text-muted">{{ $user->nik ? 'NIK ' . $user->nik : $user->email }}</div>
    </div>
</div>

@if ($user->wajib_ganti_sandi)
    <div class="login-hint small mb-3">
        <i class="bi bi-shield-lock-fill"></i>
        <div>Anda masih memakai kata sandi awal (tanggal lahir) yang mudah ditebak. Buat kata sandi baru minimal 8 karakter.</div>
    </div>
@endif

<form action="{{ route('akun.sandi.update') }}" method="POST" class="d-grid gap-3">
    @csrf @method('PUT')

    @unless ($user->wajib_ganti_sandi)
        <div>
            <label class="form-label small fw-semibold" for="password_lama">Kata Sandi Lama</label>
            <input type="password" id="password_lama" name="password_lama" class="form-control @error('password_lama') is-invalid @enderror" autocomplete="current-password" required>
            @error('password_lama')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endunless

    <div>
        <label class="form-label small fw-semibold" for="password">Kata Sandi Baru</label>
        <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" minlength="8" autocomplete="new-password" placeholder="Minimal 8 karakter" required>
        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="sandi-meter mt-2"><span id="meterBar"></span></div>
        <div class="small text-muted mt-1" id="meterText">Gunakan gabungan huruf dan angka.</div>
    </div>

    <div>
        <label class="form-label small fw-semibold" for="password_confirmation">Ulangi Kata Sandi Baru</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" minlength="8" autocomplete="new-password" required>
    </div>

    @if ($user->wajib_ganti_sandi && !$user->phone)
        <div>
            <label class="form-label small fw-semibold" for="phone">No. HP / WhatsApp <span class="text-muted fw-normal">(opsional)</span></label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="08xxxxxxxxxx">
            <div class="form-text">Agar petugas desa bisa menghubungi Anda terkait layanan.</div>
        </div>
    @endif

    <button class="btn btn-kuning py-2 fw-semibold"><i class="bi bi-check2-circle me-1"></i> Simpan Kata Sandi</button>
</form>

<div class="text-center mt-2">
    @if ($user->wajib_ganti_sandi)
        <form action="{{ route('logout') }}" method="POST">@csrf<button class="btn btn-link btn-sm text-muted text-decoration-none">Keluar</button></form>
    @else
        <a href="{{ $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" class="small text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali ke dashboard</a>
    @endif
</div>

<script>
    (function () {
        var i = document.getElementById('password'), bar = document.getElementById('meterBar'), t = document.getElementById('meterText');
        i.addEventListener('input', function () {
            var v = i.value, s = 0;
            if (v.length >= 8) s++; if (v.length >= 12) s++;
            if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++; if (/\d/.test(v)) s++; if (/[^A-Za-z0-9]/.test(v)) s++;
            if (/^\d+$/.test(v)) s = Math.min(s, 1);
            var lv = [['Terlalu pendek', '#ef4444', 15], ['Lemah', '#f97316', 35], ['Cukup', '#f59e0b', 60], ['Kuat', '#16a34a', 85], ['Sangat kuat', '#15803d', 100]][Math.min(v.length < 8 ? 0 : s, 4)];
            bar.style.width = lv[2] + '%'; bar.style.background = lv[1]; t.textContent = lv[0];
        });
    })();
</script>
@endsection
