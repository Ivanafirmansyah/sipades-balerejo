@extends('layouts.panel')

@section('title', 'Pengaturan Surat')
@section('subtitle', 'Atur kop, penandatangan, nomor, dan isi surat tanpa mengubah kode program')

@section('sidebar')
    @include('partials.sidebar-admin')
@endsection

@section('content')
<form action="{{ route('admin.surat.pengaturan.update') }}" method="POST" id="formSurat">
    @csrf @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger small"><i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}</div>
    @endif

    <div class="row g-4">
        {{-- ================= FORM ================= --}}
        <div class="col-xl-6">
            {{-- Kop surat --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-card-heading me-1"></i> Kop Surat</h6>
                    <div class="row g-2">
                        <div class="col-12"><input type="text" name="kop_baris_1" value="{{ old('kop_baris_1', $ps->kop_baris_1) }}" class="form-control" placeholder="PEMERINTAH KABUPATEN ..."></div>
                        <div class="col-12"><input type="text" name="kop_baris_2" value="{{ old('kop_baris_2', $ps->kop_baris_2) }}" class="form-control" placeholder="KECAMATAN ..."></div>
                        <div class="col-12"><input type="text" name="kop_baris_3" value="{{ old('kop_baris_3', $ps->kop_baris_3) }}" class="form-control fw-bold" placeholder="DESA ..."></div>
                        <div class="col-12"><input type="text" name="kop_alamat" value="{{ old('kop_alamat', $ps->kop_alamat) }}" class="form-control form-control-sm" placeholder="Alamat kantor desa, kode pos"></div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="tampilkan_logo" id="tampilkan_logo" value="1" @checked(old('tampilkan_logo', $ps->tampilkan_logo))>
                                <label class="form-check-label small" for="tampilkan_logo">Tampilkan logo desa di kop <span class="text-muted">(logo diatur di menu Profil &amp; Peta)</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penandatangan --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pen me-1"></i> Penandatangan</h6>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="ttd_jabatan" value="{{ old('ttd_jabatan', $ps->ttd_jabatan) }}" class="form-control" placeholder="Kepala Desa Balerejo" required>
                            <div class="form-text">Mis. "Kepala Desa Balerejo", "Lurah ...", atau "a.n. Kepala Desa, Sekretaris Desa".</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tempat surat</label>
                            <input type="text" name="tempat_surat" value="{{ old('tempat_surat', $ps->tempat_surat) }}" class="form-control" placeholder="Balerejo">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Nama Kepala Desa / Lurah <span class="text-danger">*</span></label>
                            <input type="text" name="ttd_nama" value="{{ old('ttd_nama', $ps->ttd_nama) }}" class="form-control fw-semibold" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">NIP <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="text" name="ttd_nip" value="{{ old('ttd_nip', $ps->ttd_nip) }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nomor surat --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-hash me-1"></i> Nomor Surat Otomatis</h6>
                    <input type="text" name="format_nomor" value="{{ old('format_nomor', $ps->format_nomor) }}" class="form-control font-monospace" required>
                    <div class="form-text">
                        Pakai <code>{nomor}</code> (urut, mulai 001 setiap tahun), <code>{kode}</code> (kode jenis surat), <code>{bulan_romawi}</code>, <code>{bulan}</code>, <code>{tahun}</code>.
                        Nomor dibuat otomatis saat surat ditandai <b>Selesai</b> dan nomor dikosongkan.
                        <span class="d-block mt-1">Nomor terakhir tahun {{ $ps->tahun_nomor ?? now()->year }}: <b>{{ $ps->tahun_nomor ? str_pad($ps->nomor_terakhir, 3, '0', STR_PAD_LEFT) : 'belum ada' }}</b></span>
                    </div>
                </div>
            </div>

            {{-- Kalimat surat --}}
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-text-paragraph me-1"></i> Isi Surat</h6>
                        <span class="small text-muted">Urutan di surat: pembuka → data warga → isi → penutup</span>
                    </div>
                    <label class="form-label">Kalimat pembuka</label>
                    <textarea name="kalimat_pembuka" rows="3" class="form-control mb-3 template-teks">{{ old('kalimat_pembuka', $ps->kalimat_pembuka) }}</textarea>

                    <label class="form-label">Isi khusus per jenis surat</label>
                    <select class="form-select form-select-sm mb-2" id="pilihJenis" name="jenis_id">
                        @foreach ($jenisSurats as $j)
                            <option value="{{ $j->id }}">{{ $j->nama }} ({{ $j->kode }})</option>
                        @endforeach
                    </select>
                    @foreach ($jenisSurats as $j)
                        <textarea name="isi[{{ $j->id }}]" rows="4" class="form-control template-teks isi-jenis" data-jenis="{{ $j->id }}" placeholder="Kosongkan untuk memakai isi bawaan di bawah">{{ old('isi.' . $j->id, $j->isi_surat) }}</textarea>
                    @endforeach

                    <label class="form-label mt-3">Isi bawaan <span class="text-muted fw-normal">(untuk jenis surat yang isi khususnya kosong)</span></label>
                    <textarea name="isi_bawaan" rows="3" class="form-control mb-3 template-teks">{{ old('isi_bawaan', $ps->isi_bawaan) }}</textarea>

                    <label class="form-label">Kalimat penutup</label>
                    <textarea name="kalimat_penutup" rows="2" class="form-control template-teks">{{ old('kalimat_penutup', $ps->kalimat_penutup) }}</textarea>

                    <div class="kata-kunci mt-3">
                        <div class="small fw-semibold mb-2"><i class="bi bi-magic me-1"></i> Kata kunci (klik untuk menyisipkan) · <code>**teks**</code> = cetak tebal</div>
                        @foreach ($kataKunci as $k => $ket)
                            <button type="button" class="kk-chip" data-kk="{{ $k }}" title="{{ $ket }}">{{ $k }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-biru px-4"><i class="bi bi-save me-1"></i> Simpan Pengaturan</button>
                <button type="submit" class="btn btn-outline-secondary" formaction="{{ route('admin.surat.pengaturan.pratinjau') }}" formtarget="_blank" name="pdf" value="1" formnovalidate>
                    <i class="bi bi-filetype-pdf me-1"></i> Lihat sebagai PDF
                </button>
                <button type="submit" class="btn btn-link text-muted ms-auto" form="formReset" onclick="return confirm('Kembalikan kop & kalimat ke bawaan dari Profil Desa?')">Kembalikan ke bawaan</button>
            </div>
        </div>

        {{-- ================= PRATINJAU ================= --}}
        <div class="col-xl-6">
            <div class="card pratinjau-surat">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-eye me-1"></i> Pratinjau Surat</span>
                    <span class="small text-muted" id="statusPratinjau">Berubah otomatis saat Anda mengetik</span>
                </div>
                <iframe name="pratinjauFrame" id="pratinjauFrame" title="Pratinjau surat"></iframe>
            </div>
        </div>
    </div>
</form>
<form action="{{ route('admin.surat.pengaturan.reset') }}" method="POST" id="formReset">@csrf</form>
@endsection

@push('scripts')
<script>
    (function () {
        var form = document.getElementById('formSurat'), frame = document.getElementById('pratinjauFrame');
        var status = document.getElementById('statusPratinjau'), pilih = document.getElementById('pilihJenis');
        var urlPratinjau = @json(route('admin.surat.pengaturan.pratinjau'));
        var aktif = null, timer = null;

        // tampilkan textarea isi untuk jenis surat yang dipilih
        function tampilJenis() {
            form.querySelectorAll('.isi-jenis').forEach(function (t) { t.hidden = t.dataset.jenis !== pilih.value; });
        }
        pilih.addEventListener('change', function () { tampilJenis(); perbarui(); });
        tampilJenis();

        // sisipkan kata kunci di posisi kursor pada kolom teks terakhir yang dipilih
        form.querySelectorAll('.template-teks').forEach(function (t) {
            ['focus', 'click', 'keyup', 'input'].forEach(function (ev) { t.addEventListener(ev, function () { aktif = t; }); });
        });
        document.querySelectorAll('.kk-chip').forEach(function (b) {
            b.addEventListener('click', function () {
                var t = aktif || form.querySelector('.isi-jenis:not([hidden])');
                var a = t.selectionStart ?? t.value.length, z = t.selectionEnd ?? t.value.length;
                t.value = t.value.slice(0, a) + b.dataset.kk + t.value.slice(z);
                t.focus(); t.selectionStart = t.selectionEnd = a + b.dataset.kk.length;
                perbarui();
            });
        });

        // pratinjau langsung dari isian yang belum disimpan
        function perbarui() {
            clearTimeout(timer);
            timer = setTimeout(function () {
                status.textContent = 'Memperbarui…';
                var fd = new FormData(form); fd.delete('_method');
                fetch(urlPratinjau, { method: 'POST', body: fd, headers: { 'Accept': 'text/html' }, credentials: 'same-origin' })
                    .then(function (r) { return r.text().then(function (h) { return { ok: r.ok, h: h }; }); })
                    .then(function (x) {
                        if (!x.ok) { status.textContent = 'Periksa isian (kolom wajib / format nomor)'; return; }
                        var y = frame.contentWindow ? frame.contentWindow.scrollY : 0;
                        frame.srcdoc = x.h;
                        frame.onload = function () { try { frame.contentWindow.scrollTo(0, y); } catch (e) {} };
                        status.textContent = 'Berubah otomatis saat Anda mengetik';
                    })
                    .catch(function () { status.textContent = 'Gagal memuat pratinjau'; });
            }, 450);
        }
        form.addEventListener('input', perbarui);
        form.addEventListener('change', perbarui);
        perbarui();
    })();
</script>
@endpush
