<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\Berita;
use App\Models\Formulir;
use App\Models\JenisSurat;
use App\Models\Penduduk;
use App\Models\Pengaduan;
use App\Models\PerangkatDesa;
use App\Models\ProfilDesa;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        ProfilDesa::create([
            'nama_desa' => 'Desa Sukamaju',
            'kecamatan' => 'Cikarang Utara',
            'kabupaten' => 'Bekasi',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '17530',
            'deskripsi' => 'Desa Sukamaju adalah desa yang maju, mandiri, dan sejahtera dengan masyarakat yang gotong royong serta pelayanan publik yang transparan dan mudah diakses.',
            'sejarah' => 'Desa Sukamaju berdiri sejak tahun 1952. Nama Sukamaju diambil dari harapan para pendiri agar desa ini senantiasa maju dan sejahtera. Seiring berjalannya waktu, desa ini berkembang menjadi salah satu desa dengan potensi pertanian dan UMKM yang unggul.',
            'visi' => 'Terwujudnya Desa Sukamaju yang maju, mandiri, sejahtera, dan berbudaya melalui tata kelola pemerintahan yang baik dan pelayanan publik yang prima.',
            'misi' => "1. Meningkatkan kualitas pelayanan publik yang cepat, mudah, dan transparan.\n2. Mendorong pertumbuhan ekonomi desa melalui UMKM dan pertanian.\n3. Meningkatkan infrastruktur dan fasilitas umum desa.\n4. Memberdayakan masyarakat melalui pendidikan dan pelatihan.\n5. Menjaga nilai budaya dan gotong royong.",
            'alamat_kantor' => 'Jl. Raya Sukamaju No. 1, RT 01/RW 01, Desa Sukamaju, Kecamatan Cikarang Utara, Kabupaten Bekasi',
            'telepon' => '021-8901234',
            'email' => 'info@sukamaju.desa.id',
            'peta_embed' => '<iframe src="https://www.openstreetmap.org/export/embed.html?bbox=106.98%2C-6.32%2C107.02%2C-6.28&amp;layer=mapnik&amp;marker=-6.30%2C107.00" style="border:0;width:100%;height:100%;" allowfullscreen loading="lazy"></iframe>',
        ]);

        $admin = User::create([
            'name' => 'Administrator Desa',
            'email' => 'admin@desa.test',
            'phone' => '081200000001',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Admin Desa 2',
            'email' => 'admin2@desa.test',
            'phone' => '081200000004',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $warga = User::create([
            'name' => 'Budi Santoso',
            'email' => 'warga@desa.test',
            'phone' => '081200000002',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        $warga2 = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@desa.test',
            'phone' => '081200000003',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        $perangkats = [
            ['nama' => 'H. Ahmad Fauzi', 'jabatan' => 'Kepala Desa', 'keterangan' => 'Periode 2023-2029'],
            ['nama' => 'Dedi Kurniawan', 'jabatan' => 'Sekretaris Desa', 'keterangan' => null],
            ['nama' => 'Rina Marlina', 'jabatan' => 'Kaur Keuangan', 'keterangan' => null],
            ['nama' => 'Agus Setiawan', 'jabatan' => 'Kaur Pemerintahan', 'keterangan' => null],
            ['nama' => 'Dewi Lestari', 'jabatan' => 'Kaur Pelayanan', 'keterangan' => null],
            ['nama' => 'Joko Susilo', 'jabatan' => 'Kadus Sukamaju', 'keterangan' => null],
            ['nama' => 'Slamet Riyadi', 'jabatan' => 'Ketua BPD', 'keterangan' => 'Badan Permusyawaratan Desa'],
        ];
        foreach ($perangkats as $p) {
            PerangkatDesa::create($p);
        }

        $jenisSurats = [
            ['nama' => 'Surat Keterangan Domisili', 'kode' => 'SKD', 'keterangan' => 'Keterangan tempat tinggal warga'],
            ['nama' => 'Surat Keterangan Usaha', 'kode' => 'SKU', 'keterangan' => 'Keterangan usaha mikro dan kecil'],
            ['nama' => 'Surat Keterangan Tidak Mampu', 'kode' => 'SKTM', 'keterangan' => 'Keterangan untuk bantuan sosial'],
            ['nama' => 'Surat Keterangan Kelahiran', 'kode' => 'SKK', 'keterangan' => 'Keterangan kelahiran anak'],
            ['nama' => 'Surat Keterangan Kematian', 'kode' => 'SKM', 'keterangan' => 'Keterangan kematian warga'],
            ['nama' => 'Surat Pengantar SKCK', 'kode' => 'SP-SKCK', 'keterangan' => 'Pengantar pembuatan SKCK'],
        ];
        $jenis = [];
        foreach ($jenisSurats as $j) {
            $jenis[] = JenisSurat::create($j + ['is_aktif' => true]);
        }

        $penduduksData = [
            ['nik' => '3216010101010001', 'no_kk' => '3216010101010000', 'nama' => 'Budi Santoso', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1985-01-01', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Karyawan Swasta', 'alamat' => 'Jl. Melati No. 5', 'rt' => '01', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => $warga->id],
            ['nik' => '3216010101010002', 'no_kk' => '3216010101010000', 'nama' => 'Siti Aminah', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1988-05-12', 'jenis_kelamin' => 'P', 'agama' => 'Islam', 'pekerjaan' => 'Ibu Rumah Tangga', 'alamat' => 'Jl. Melati No. 5', 'rt' => '01', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => $warga2->id],
            ['nik' => '3216010101010003', 'no_kk' => '3216010101010000', 'nama' => 'Rizky Santoso', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '2012-08-20', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Pelajar', 'alamat' => 'Jl. Melati No. 5', 'rt' => '01', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => null],
            ['nik' => '3216010101010004', 'no_kk' => '3216010101010100', 'nama' => 'Agus Setiawan', 'tempat_lahir' => 'Bandung', 'tanggal_lahir' => '1980-03-15', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Petani', 'alamat' => 'Jl. Kenanga No. 2', 'rt' => '02', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => null],
            ['nik' => '3216010101010005', 'no_kk' => '3216010101010100', 'nama' => 'Dewi Lestari', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1983-11-23', 'jenis_kelamin' => 'P', 'agama' => 'Islam', 'pekerjaan' => 'Wirausaha', 'alamat' => 'Jl. Kenanga No. 2', 'rt' => '02', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => null],
            ['nik' => '3216010101010006', 'no_kk' => '3216010101010200', 'nama' => 'Slamet Riyadi', 'tempat_lahir' => 'Solo', 'tanggal_lahir' => '1975-07-07', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Guru', 'alamat' => 'Jl. Anggrek No. 8', 'rt' => '03', 'rw' => '02', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => null],
            ['nik' => '3216010101010007', 'no_kk' => '3216010101010200', 'nama' => 'Rina Marlina', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1978-09-02', 'jenis_kelamin' => 'P', 'agama' => 'Islam', 'pekerjaan' => 'Perawat', 'alamat' => 'Jl. Anggrek No. 8', 'rt' => '03', 'rw' => '02', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => null],
            ['nik' => '3216010101010008', 'no_kk' => '3216010101010300', 'nama' => 'Joko Susilo', 'tempat_lahir' => 'Semarang', 'tanggal_lahir' => '1970-12-30', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Pensiunan', 'alamat' => 'Jl. Mawar No. 10', 'rt' => '04', 'rw' => '02', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => null],
            ['nik' => '3216010101010009', 'no_kk' => '3216010101010300', 'nama' => 'Sri Wahyuni', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1972-02-14', 'jenis_kelamin' => 'P', 'agama' => 'Islam', 'pekerjaan' => 'Ibu Rumah Tangga', 'alamat' => 'Jl. Mawar No. 10', 'rt' => '04', 'rw' => '02', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => null],
            ['nik' => '3216010101010010', 'no_kk' => '3216010101010400', 'nama' => 'Dedi Kurniawan', 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '1990-06-18', 'jenis_kelamin' => 'L', 'agama' => 'Kristen', 'pekerjaan' => 'Karyawan BUMN', 'alamat' => 'Jl. Dahlia No. 3', 'rt' => '05', 'rw' => '03', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => null],
            ['nik' => '3216010101010011', 'no_kk' => '3216010101010400', 'nama' => 'Maria Kristina', 'tempat_lahir' => 'Cirebon', 'tanggal_lahir' => '1992-10-10', 'jenis_kelamin' => 'P', 'agama' => 'Kristen', 'pekerjaan' => 'Guru', 'alamat' => 'Jl. Dahlia No. 3', 'rt' => '05', 'rw' => '03', 'dusun' => 'Sukamaju', 'status' => 'Anggota', 'user_id' => null],
            ['nik' => '3216010101010012', 'no_kk' => '3216010101010500', 'nama' => 'H. Ahmad Fauzi', 'tempat_lahir' => 'Bekasi', 'tanggal_lahir' => '1968-04-04', 'jenis_kelamin' => 'L', 'agama' => 'Islam', 'pekerjaan' => 'Kepala Desa', 'alamat' => 'Jl. Raya Sukamaju No. 1', 'rt' => '01', 'rw' => '01', 'dusun' => 'Sukamaju', 'status' => 'Kepala Keluarga', 'user_id' => $admin->id],
        ];
        $penduduks = [];
        foreach ($penduduksData as $p) {
            $penduduks[] = Penduduk::create($p);
        }

        Surat::create([
            'user_id' => $warga->id,
            'penduduk_id' => $penduduks[0]->id,
            'jenis_surat_id' => $jenis[0]->id,
            'keperluan' => 'Pembuatan rekening bank',
            'status' => 'selesai',
            'no_surat' => '470/001/DS-SKM/2026',
            'diproses_at' => now()->subDays(3),
            'selesai_at' => now()->subDays(2),
        ]);
        Surat::create([
            'user_id' => $warga2->id,
            'penduduk_id' => $penduduks[1]->id,
            'jenis_surat_id' => $jenis[2]->id,
            'keperluan' => 'Pengajuan bantuan pendidikan',
            'status' => 'diproses',
            'diproses_at' => now()->subDay(),
        ]);
        Surat::create([
            'user_id' => $warga->id,
            'penduduk_id' => $penduduks[0]->id,
            'jenis_surat_id' => $jenis[1]->id,
            'keperluan' => 'Persyaratan pinjaman modal usaha',
            'status' => 'menunggu',
        ]);

        Berita::create([
            'judul' => 'Musyawarah Desa Penyusunan RKPDes 2027',
            'slug' => 'musyawarah-desa-penyusunan-rkpdes-2027',
            'isi' => "Pemerintah Desa Sukamaju mengundang seluruh perwakilan warga untuk menghadiri Musyawarah Desa dalam rangka penyusunan Rencana Kerja Pemerintah Desa (RKPDes) tahun 2027.\n\nKegiatan akan dilaksanakan di Balai Desa Sukamaju. Kehadiran seluruh elemen masyarakat sangat diharapkan demi tersusunnya program pembangunan yang tepat sasaran.",
            'kategori' => 'berita',
            'penulis' => 'Administrator Desa',
            'is_published' => true,
            'published_at' => now()->subDays(2),
        ]);
        Berita::create([
            'judul' => 'Pengumuman Jadwal Posyandu Balita Bulan Ini',
            'slug' => 'pengumuman-jadwal-posyandu-balita',
            'isi' => "Diberitahukan kepada seluruh orang tua balita bahwa kegiatan Posyandu akan dilaksanakan pada minggu kedua bulan ini di masing-masing posyandu RT/RW.\n\nMohon membawa buku KIA dan hadir tepat waktu.",
            'kategori' => 'pengumuman',
            'penulis' => 'Administrator Desa',
            'is_published' => true,
            'published_at' => now()->subDays(5),
        ]);
        Berita::create([
            'judul' => 'Gotong Royong Bersihkan Saluran Air Desa',
            'slug' => 'gotong-royong-bersihkan-saluran-air',
            'isi' => "Dalam rangka menyambut musim hujan, warga desa bersama perangkat desa melaksanakan gotong royong membersihkan saluran air di sepanjang jalan utama desa.\n\nKegiatan berjalan lancar dan diikuti oleh ratusan warga dari berbagai RT.",
            'kategori' => 'berita',
            'penulis' => 'Administrator Desa',
            'is_published' => true,
            'published_at' => now()->subDays(8),
        ]);

        Agenda::create([
            'judul' => 'Musyawarah Desa RKPDes',
            'deskripsi' => 'Musyawarah penyusunan RKPDes tahun anggaran berikutnya.',
            'tanggal' => now()->addDays(7)->toDateString(),
            'jam' => '09:00',
            'lokasi' => 'Balai Desa Sukamaju',
        ]);
        Agenda::create([
            'judul' => 'Posyandu Balita',
            'deskripsi' => 'Pemeriksaan kesehatan balita dan imunisasi.',
            'tanggal' => now()->addDays(12)->toDateString(),
            'jam' => '08:00',
            'lokasi' => 'Posyandu Melati',
        ]);
        Agenda::create([
            'judul' => 'Pelatihan UMKM Digital',
            'deskripsi' => 'Pelatihan pemasaran produk UMKM secara online.',
            'tanggal' => now()->addDays(20)->toDateString(),
            'jam' => '13:00',
            'lokasi' => 'Aula Kantor Desa',
        ]);

        Pengaduan::create([
            'user_id' => $warga->id,
            'nama_pelapor' => 'Budi Santoso',
            'kategori' => 'Infrastruktur',
            'isi' => 'Lampu jalan di RT 01 RW 01 sudah mati sejak seminggu yang lalu. Mohon segera diperbaiki karena jalan menjadi gelap di malam hari.',
            'lokasi' => 'RT 01 / RW 01',
            'status' => 'diproses',
            'tanggapan' => 'Terima kasih atas laporannya. Perbaikan lampu jalan dijadwalkan minggu ini.',
            'tanggapan_at' => now()->subDay(),
        ]);
        Pengaduan::create([
            'user_id' => $warga2->id,
            'nama_pelapor' => 'Siti Aminah',
            'kategori' => 'Kebersihan & Lingkungan',
            'isi' => 'Sampah menumpuk di dekat pasar desa dan belum diangkut selama beberapa hari.',
            'lokasi' => 'Dekat Pasar Desa',
            'status' => 'baru',
        ]);

        Formulir::create([
            'judul' => 'Formulir Pendataan Bantuan Sosial',
            'deskripsi' => 'Isi formulir untuk pendataan penerima bantuan sosial desa.',
            'link' => 'https://forms.gle/contoh-formulir',
            'is_aktif' => true,
        ]);

        $this->call(FormulirDinamisSeeder::class);
    }
}
