<?php

namespace Tests\Feature;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_halaman_publik_dapat_diakses(): void
    {
        foreach (['/', '/profil', '/struktur', '/peta', '/berita', '/agenda', '/layanan', '/data-penduduk', '/kontak', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }

        $berita = Berita::firstOrFail();
        $this->get('/berita/' . $berita->slug)->assertOk();
    }

    public function test_warga_dapat_mengakses_dashboard_dan_mengajukan_surat(): void
    {
        $user = User::where('email', 'warga@desa.test')->firstOrFail();
        $jenis = JenisSurat::firstOrFail();

        $this->actingAs($user);

        foreach (['/dashboard', '/dashboard/pengajuan-surat', '/dashboard/riwayat-surat', '/dashboard/pengaduan', '/dashboard/pengaduan/buat'] as $url) {
            $this->get($url)->assertOk();
        }

        $response = $this->post('/dashboard/pengajuan-surat', [
            'jenis_surat_id' => $jenis->id,
            'keperluan' => 'Keperluan pengujian',
            'nik' => '3216010101010099',
            'nama' => 'Warga Uji',
            'jenis_kelamin' => 'L',
            'rt' => '01',
            'rw' => '01',
        ]);

        $response->assertRedirect('/dashboard/riwayat-surat');
        $this->assertDatabaseHas('surats', ['user_id' => $user->id, 'keperluan' => 'Keperluan pengujian']);

        $this->post('/dashboard/pengaduan', [
            'kategori' => 'Infrastruktur',
            'isi' => 'Pengaduan pengujian',
        ])->assertRedirect('/dashboard/pengaduan');
        $this->assertDatabaseHas('pengaduans', ['user_id' => $user->id, 'isi' => 'Pengaduan pengujian']);
    }

    public function test_admin_dapat_mengakses_semua_halaman_dan_export_pdf(): void
    {
        $admin = User::where('email', 'admin@desa.test')->firstOrFail();
        $this->actingAs($admin);

        $urls = [
            '/admin', '/admin/penduduk', '/admin/penduduk/create', '/admin/surat', '/admin/jenis-surat',
            '/admin/berita', '/admin/berita/create', '/admin/agenda', '/admin/agenda/create',
            '/admin/pengaduan', '/admin/perangkat', '/admin/perangkat/create', '/admin/profil',
            '/admin/users', '/admin/users/create', '/admin/formulir', '/admin/formulir/create',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertOk();
        }

        $penduduk = Penduduk::firstOrFail();
        $this->get('/admin/penduduk/' . $penduduk->id . '/edit')->assertOk();

        $surat = Surat::firstOrFail();
        $this->get('/admin/surat/' . $surat->id . '/pdf')->assertOk();
        $this->get('/admin/penduduk/export/pdf')->assertOk();
        $this->get('/admin/surat/export/pdf')->assertOk();
        $this->get('/admin/pengaduan/export/pdf')->assertOk();

        $pengaduan = Pengaduan::firstOrFail();
        $this->get('/admin/pengaduan/' . $pengaduan->id)->assertOk();
    }

    public function test_admin_dapat_memproses_status_surat(): void
    {
        $admin = User::where('email', 'admin@desa.test')->firstOrFail();
        $surat = Surat::where('status', 'menunggu')->firstOrFail();

        $this->actingAs($admin)->patch('/admin/surat/' . $surat->id, [
            'status' => 'selesai',
            'no_surat' => '470/999/DS/2026',
        ])->assertRedirect();

        $surat->refresh();
        $this->assertSame('selesai', $surat->status);
        $this->assertNotNull($surat->selesai_at);
    }

    public function test_warga_tidak_bisa_mengakses_admin(): void
    {
        $user = User::where('email', 'user@example.com')->first()
            ?? User::where('role', 'user')->firstOrFail();

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_export_pdf_penduduk_berisi_filter(): void
    {
        $admin = User::where('email', 'admin@desa.test')->firstOrFail();

        $response = $this->actingAs($admin)->get('/admin/penduduk/export/pdf?rt=01&rw=01');
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
