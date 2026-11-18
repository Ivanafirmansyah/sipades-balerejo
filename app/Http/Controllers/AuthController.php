<?php

namespace App\Http\Controllers;

use App\Models\Penduduk;
use App\Models\User;
use App\Services\AkunWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Masuk dengan NIK (warga) atau email (admin / akun lama).
     * Warga tidak perlu mendaftar: akunnya otomatis ada bila admin sudah memasukkan data penduduknya.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string', 'max:150'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'NIK atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $login = preg_replace('/\s+/', '', $request->login);
        $pakaiNik = (bool) preg_match('/^\d{16}$/', $login);

        // Batas percobaan per NIK/email (bukan per IP), agar tanggal lahir tidak bisa ditebak-tebak
        if ($terkunci = AkunWarga::cekKunci($login)) {
            return $this->gagal($terkunci);
        }

        if ($pakaiNik) {
            $user = User::where('nik', $login)->first();

            // Data penduduk ada tetapi akun belum terbentuk (mis. data diimpor langsung ke database)
            if (! $user && ($penduduk = Penduduk::where('nik', $login)->first())) {
                $user = AkunWarga::sinkron($penduduk);
            }

            if (! $user) {
                return $this->gagal('NIK belum terdaftar di data penduduk desa. Silakan hubungi kantor desa untuk didata.'
                    . AkunWarga::catatGagal($login));
            }

            if ($user->wajib_ganti_sandi && $user->role === 'user' && ! $user->penduduk?->tanggal_lahir) {
                return $this->gagal('Tanggal lahir Anda belum tercatat di data desa, jadi akun belum bisa dipakai. Silakan hubungi kantor desa.');
            }
        } else {
            $user = User::where('email', $login)->first();
        }

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->gagal(($pakaiNik
                ? 'Kata sandi salah. Jika baru pertama masuk, gunakan tanggal lahir Anda (format DDMMYYYY, contoh 17081990).'
                : 'Email atau kata sandi salah.') . AkunWarga::catatGagal($login));
        }

        AkunWarga::bukaKunci($login);
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        if ($user->wajib_ganti_sandi) {
            return redirect()->route('akun.sandi')->with('success', 'Selamat datang, ' . $user->name . '! Silakan buat kata sandi baru untuk keamanan akun Anda.');
        }

        return $this->keTujuan($user)->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    /**
     * Arahkan ke halaman yang tadinya ingin dibuka, kecuali halaman admin untuk warga biasa
     * (mis. perangkat bersama yang sebelumnya membuka /admin) -> ke dashboard warga.
     */
    private function keTujuan(User $user)
    {
        $awal = $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard');
        $tujuan = session()->pull('url.intended');

        if ($tujuan && ! $user->isAdmin() && preg_match('#^/admin(/|$)#', parse_url($tujuan, PHP_URL_PATH) ?? '')) {
            $tujuan = null;
        }

        return redirect()->to($tujuan ?: $awal);
    }

    private function gagal(string $pesan)
    {
        return back()->withErrors(['login' => $pesan])->onlyInput('login');
    }

    /** Halaman "Daftar": warga tidak mendaftar sendiri, cukup didata admin lalu masuk pakai NIK. */
    public function showRegister()
    {
        return view('auth.register');
    }

    /* ---------------- Ganti kata sandi ---------------- */

    public function showSandi()
    {
        return view('auth.sandi', ['user' => auth()->user()]);
    }

    public function updateSandi(Request $request)
    {
        $user = $request->user();

        $rules = [
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
        // Saat ganti sandi pertama kali (sandi awal), sandi lama tidak perlu diminta lagi
        if (! $user->wajib_ganti_sandi) {
            $rules['password_lama'] = ['required', 'current_password'];
        }

        $request->validate($rules, [
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password_lama.required' => 'Kata sandi lama wajib diisi.',
            'password_lama.current_password' => 'Kata sandi lama salah.',
        ]);

        $sandiAwal = $user->penduduk ? AkunWarga::sandiAwal($user->penduduk) : null;
        if ($sandiAwal && $request->password === $sandiAwal) {
            return back()->withErrors(['password' => 'Jangan memakai tanggal lahir sebagai kata sandi. Buat kata sandi lain.']);
        }
        if ($user->nik && $request->password === $user->nik) {
            return back()->withErrors(['password' => 'Jangan memakai NIK sebagai kata sandi.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'wajib_ganti_sandi' => false,
            'phone' => $request->filled('phone') ? $request->phone : $user->phone,
        ])->save();

        $request->session()->regenerate();

        return $this->keTujuan($user)->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
