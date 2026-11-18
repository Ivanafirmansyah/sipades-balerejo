<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgendaController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\FormulirController;
use App\Http\Controllers\Admin\LokasiController;
use App\Http\Controllers\Admin\PendudukController;
use App\Http\Controllers\Admin\PengaduanController as AdminPengaduanController;
use App\Http\Controllers\Admin\PengaturanSuratController;
use App\Http\Controllers\Admin\PerangkatController;
use App\Http\Controllers\Admin\ProfilController;
use App\Http\Controllers\Admin\SuratController as AdminSuratController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormulirPublikController;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\SitusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik (Guest)
|--------------------------------------------------------------------------
*/
Route::get('/', [SitusController::class, 'home'])->name('home');
Route::get('/profil', [SitusController::class, 'profil'])->name('profil');
Route::get('/struktur', [SitusController::class, 'struktur'])->name('struktur');
Route::get('/peta', [SitusController::class, 'peta'])->name('peta');
Route::get('/berita', [SitusController::class, 'berita'])->name('berita.index');
Route::get('/berita/{slug}', [SitusController::class, 'beritaShow'])->name('berita.show');
Route::get('/agenda', [SitusController::class, 'agenda'])->name('agenda.index');
Route::get('/layanan', [SitusController::class, 'layanan'])->name('layanan');
Route::get('/data-penduduk', [SitusController::class, 'dataPenduduk'])->name('penduduk.publik');
Route::get('/kontak', [SitusController::class, 'kontak'])->name('kontak');

// Formulir dinamis (Form Builder) — bisa diisi offline, dikirim otomatis saat online
Route::get('/formulir', [FormulirPublikController::class, 'index'])->name('formulir.index');
Route::get('/formulir/{slug}', [FormulirPublikController::class, 'show'])->name('formulir.show');
Route::post('/formulir/{slug}/kirim', [FormulirPublikController::class, 'kirim'])->name('formulir.kirim')->middleware('throttle:30,1');
// Koreksi isian berversi (optimistic concurrency) + data terbaru untuk menyelesaikan konflik
Route::post('/formulir/{slug}/ubah', [FormulirPublikController::class, 'ubah'])->name('formulir.ubah')->middleware('throttle:30,1');
Route::get('/formulir/{slug}/isian/{uuid}', [FormulirPublikController::class, 'isian'])->name('formulir.isian')->middleware('throttle:60,1');

// Progressive Web App
Route::get('/manifest.webmanifest', [PwaController::class, 'manifest'])->name('pwa.manifest');
Route::get('/offline', [PwaController::class, 'offline'])->name('pwa.offline');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Ganti kata sandi (wajib saat pertama masuk dengan sandi awal tanggal lahir)
Route::middleware('auth')->group(function () {
    Route::get('/akun/kata-sandi', [AuthController::class, 'showSandi'])->name('akun.sandi');
    Route::put('/akun/kata-sandi', [AuthController::class, 'updateSandi'])->name('akun.sandi.update');
});

/*
|--------------------------------------------------------------------------
| Dashboard User (Warga)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('dashboard')->name('user.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/pengajuan-surat', [DashboardController::class, 'suratCreate'])->name('surat.create');
    Route::post('/pengajuan-surat', [DashboardController::class, 'suratStore'])->name('surat.store');
    Route::get('/riwayat-surat', [DashboardController::class, 'suratIndex'])->name('surat.index');
    Route::get('/riwayat-surat/{surat}/pdf', [DashboardController::class, 'suratPdf'])->name('surat.pdf');

    Route::get('/pengaduan', [DashboardController::class, 'pengaduanIndex'])->name('pengaduan.index');
    Route::get('/pengaduan/buat', [DashboardController::class, 'pengaduanCreate'])->name('pengaduan.create');
    Route::post('/pengaduan', [DashboardController::class, 'pengaduanStore'])->name('pengaduan.store');
});

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('penduduk/{penduduk}/reset-sandi', [PendudukController::class, 'resetSandi'])->name('penduduk.reset-sandi');
    Route::get('penduduk/export/pdf', [ExportController::class, 'penduduk'])->name('penduduk.export');
    Route::resource('penduduk', PendudukController::class)->parameters(['penduduk' => 'penduduk'])->except(['show']);

    // Pengaturan struktur isi surat (kop, penandatangan, nomor, kalimat, isi per jenis)
    Route::get('surat/pengaturan', [PengaturanSuratController::class, 'edit'])->name('surat.pengaturan');
    Route::put('surat/pengaturan', [PengaturanSuratController::class, 'update'])->name('surat.pengaturan.update');
    Route::post('surat/pengaturan/reset', [PengaturanSuratController::class, 'reset'])->name('surat.pengaturan.reset');
    Route::match(['get', 'post'], 'surat/pengaturan/pratinjau', [PengaturanSuratController::class, 'pratinjau'])->name('surat.pengaturan.pratinjau');
    Route::get('surat/export/pdf', [AdminSuratController::class, 'exportPdf'])->name('surat.export');
    Route::get('surat/{surat}/pdf', [AdminSuratController::class, 'pdf'])->name('surat.pdf');
    Route::get('surat', [AdminSuratController::class, 'index'])->name('surat.index');
    Route::patch('surat/{surat}', [AdminSuratController::class, 'update'])->name('surat.update');

    Route::get('jenis-surat', [AdminSuratController::class, 'jenisIndex'])->name('jenis.index');
    Route::post('jenis-surat', [AdminSuratController::class, 'jenisStore'])->name('jenis.store');
    Route::put('jenis-surat/{jenisSurat}', [AdminSuratController::class, 'jenisUpdate'])->name('jenis.update');
    Route::delete('jenis-surat/{jenisSurat}', [AdminSuratController::class, 'jenisDestroy'])->name('jenis.destroy');

    Route::resource('berita', BeritaController::class)->parameters(['berita' => 'berita'])->except(['show']);
    Route::resource('agenda', AgendaController::class)->parameters(['agenda' => 'agenda'])->except(['show']);

    Route::get('pengaduan/export/pdf', [ExportController::class, 'pengaduan'])->name('pengaduan.export');
    Route::get('pengaduan', [AdminPengaduanController::class, 'index'])->name('pengaduan.index');
    Route::get('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'show'])->name('pengaduan.show');
    Route::patch('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'update'])->name('pengaduan.update');
    Route::delete('pengaduan/{pengaduan}', [AdminPengaduanController::class, 'destroy'])->name('pengaduan.destroy');

    // Peta Fasilitas Desa (titik fasilitas umum)
    Route::resource('lokasi', LokasiController::class)->parameters(['lokasi' => 'lokasi'])->except(['show']);
    Route::resource('perangkat', PerangkatController::class)->parameters(['perangkat' => 'perangkat'])->except(['show']);
    Route::get('formulir/{formulir}/isian', [FormulirController::class, 'jawaban'])->name('formulir.jawaban');
    Route::get('formulir/{formulir}/isian/csv', [FormulirController::class, 'jawabanCsv'])->name('formulir.jawaban.csv');
    Route::patch('formulir/{formulir}/isian/{jawaban}', [FormulirController::class, 'jawabanUpdate'])->name('formulir.jawaban.update');
    Route::delete('formulir/{formulir}/isian/{jawaban}', [FormulirController::class, 'jawabanDestroy'])->name('formulir.jawaban.destroy');
    Route::resource('formulir', FormulirController::class)->parameters(['formulir' => 'formulir'])->except(['show']);
    Route::resource('users', AdminController::class)->parameters(['users' => 'user'])->except(['show']);

    Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
});
