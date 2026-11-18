<?php

namespace Database\Seeders;

use App\Models\Formulir;
use App\Models\JenisSurat;
use App\Support\FormSchema;
use Illuminate\Database\Seeder;

/** Contoh formulir dinamis buatan Form Builder. Aman dijalankan berulang. */
class FormulirDinamisSeeder extends Seeder
{
    public function run(): void
    {
        Formulir::updateOrCreate(['slug' => 'pendataan-bantuan-sosial-2026'], [
            'judul' => 'Pendataan Bantuan Sosial 2026',
            'tipe' => 'dinamis',
            'kategori' => 'pendataan',
            'deskripsi' => 'Pendataan keluarga calon penerima bantuan sosial tahun 2026. Bisa diisi tanpa sinyal — data terkirim otomatis saat online.',
            'butuh_login' => false,
            'is_aktif' => true,
            'pesan_sukses' => 'Terima kasih, data keluarga Anda sudah masuk ke pendataan bansos desa.',
            'schema' => FormSchema::normalize(['fields' => [
                ['type' => 'section', 'label' => 'Data Kepala Keluarga'],
                ['type' => 'text', 'label' => 'Nama Kepala Keluarga', 'required' => true],
                ['type' => 'nik', 'label' => 'NIK Kepala Keluarga', 'required' => true, 'width' => 'half'],
                ['type' => 'tel', 'label' => 'No. HP / WhatsApp', 'width' => 'half'],
                ['type' => 'number', 'label' => 'Jumlah Anggota Keluarga', 'required' => true, 'min' => 1, 'max' => 30, 'width' => 'half'],
                ['type' => 'select', 'label' => 'Pekerjaan Utama', 'required' => true, 'width' => 'half', 'options' => ['Petani', 'Buruh Harian', 'Pedagang', 'Nelayan', 'Tidak Bekerja', 'Lainnya']],
                ['type' => 'section', 'label' => 'Kondisi Rumah'],
                ['type' => 'radio', 'label' => 'Status Kepemilikan Rumah', 'required' => true, 'options' => ['Milik Sendiri', 'Sewa / Kontrak', 'Menumpang']],
                ['type' => 'checkbox', 'label' => 'Bantuan yang Pernah Diterima', 'options' => ['PKH', 'BPNT', 'BLT Dana Desa', 'Belum Pernah']],
                ['type' => 'textarea', 'label' => 'Keterangan Tambahan', 'placeholder' => 'Ceritakan kondisi keluarga bila perlu'],
            ]]),
        ]);

        $sku = JenisSurat::where('kode', 'SKU')->first();
        if ($sku) {
            Formulir::updateOrCreate(['slug' => 'surat-keterangan-usaha-online'], [
                'judul' => 'Surat Keterangan Usaha (Online)',
                'tipe' => 'dinamis',
                'kategori' => 'surat',
                'jenis_surat_id' => $sku->id,
                'deskripsi' => 'Ajukan Surat Keterangan Usaha. Isian otomatis masuk ke antrean surat desa.',
                'butuh_login' => true,
                'is_aktif' => true,
                'pesan_sukses' => 'Pengajuan Surat Keterangan Usaha Anda sudah diterima.',
                'schema' => FormSchema::normalize(['fields' => [
                    ['type' => 'section', 'label' => 'Data Usaha'],
                    ['type' => 'text', 'label' => 'Nama Usaha', 'required' => true],
                    ['type' => 'select', 'label' => 'Bidang Usaha', 'required' => true, 'width' => 'half', 'options' => ['Kuliner', 'Perdagangan', 'Pertanian', 'Kerajinan', 'Jasa', 'Lainnya']],
                    ['type' => 'date', 'label' => 'Mulai Usaha Sejak', 'width' => 'half'],
                    ['type' => 'textarea', 'label' => 'Alamat Tempat Usaha', 'required' => true],
                    ['type' => 'text', 'label' => 'Keperluan Surat', 'required' => true, 'placeholder' => 'Contoh: pengajuan pinjaman modal KUR'],
                ]]),
            ]);
        }
    }
}
