@extends('layouts.auth')

@section('title', 'Masuk')
@section('heading', 'Masuk ke SIPADES')
@section('subheading', 'Warga cukup masuk dengan NIK — tidak perlu mendaftar')

@section('content')
<form action="{{ route('login') }}" method="POST" class="d-grid gap-3">
    @csrf

    <div>
        <label class="form-label small fw-semibold" for="login">NIK <span class="text-muted fw-normal">(atau email untuk admin)</span></label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-person-vcard"></i></span>
            <input type="text" id="login" name="login" value="{{ old('login') }}" class="form-control @error('login') is-invalid @enderror"
                   placeholder="16 digit NIK" autocomplete="username" required autofocus>
        </div>
        @error('login')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>

    <div>
        <label class="form-label small fw-semibold" for="password">Kata Sandi</label>
        <div class="input-group">
            <span class="input-group-text bg-white"><i class="bi bi-lock"></i></span>
            <input type="password" id="password" name="password" class="form-control" placeholder="Kata sandi" autocomplete="current-password" required>
            <button type="button" class="btn btn-outline-secondary" id="lihatSandi" title="Lihat kata sandi"><i class="bi bi-eye"></i></button>
        </div>
    </div>

    <div class="login-hint small">
        <i class="bi bi-info-circle-fill"></i>
        <div><b>Pertama kali masuk?</b> Gunakan <b>tanggal lahir</b> Anda sebagai kata sandi dengan format <b>DDMMYYYY</b> — contoh lahir 17 Agustus 1990 ketik <code>17081990</code>. Setelah itu Anda diminta membuat kata sandi baru.</div>
    </div>

    <div class="form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input" value="1">
        <label for="remember" class="form-check-label small">Ingat saya</label>
    </div>

    <button class="btn btn-biru py-2 fw-semibold"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>

    <div class="text-center small text-muted">
        NIK belum terdaftar? <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Lihat caranya</a>
    </div>
    <div class="text-center">
        <a href="{{ route('home') }}" class="small text-decoration-none text-muted"><i class="bi bi-arrow-left me-1"></i>Kembali ke beranda</a>
    </div>
</form>

<script>
    document.getElementById('lihatSandi').addEventListener('click', function () {
        var i = document.getElementById('password'); var show = i.type === 'password';
        i.type = show ? 'text' : 'password';
        this.innerHTML = '<i class="bi bi-eye' + (show ? '-slash' : '') + '"></i>';
    });
    // NIK: buang spasi yang ikut tersalin
    document.getElementById('login').addEventListener('blur', function () { if (/^[\d\s]+$/.test(this.value)) this.value = this.value.replace(/\s+/g, ''); });
</script>
@endsection
