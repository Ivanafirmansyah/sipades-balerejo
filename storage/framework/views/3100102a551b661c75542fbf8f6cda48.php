<?php $__env->startSection('title', 'Pengaturan Surat'); ?>
<?php $__env->startSection('subtitle', 'Atur kop, penandatangan, nomor, dan isi surat tanpa mengubah kode program'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.surat.pengaturan.update')); ?>" method="POST" id="formSurat">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger small"><i class="bi bi-exclamation-triangle me-1"></i> <?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        
        <div class="col-xl-6">
            
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-card-heading me-1"></i> Kop Surat</h6>
                    <div class="row g-2">
                        <div class="col-12"><input type="text" name="kop_baris_1" value="<?php echo e(old('kop_baris_1', $ps->kop_baris_1)); ?>" class="form-control" placeholder="PEMERINTAH KABUPATEN ..."></div>
                        <div class="col-12"><input type="text" name="kop_baris_2" value="<?php echo e(old('kop_baris_2', $ps->kop_baris_2)); ?>" class="form-control" placeholder="KECAMATAN ..."></div>
                        <div class="col-12"><input type="text" name="kop_baris_3" value="<?php echo e(old('kop_baris_3', $ps->kop_baris_3)); ?>" class="form-control fw-bold" placeholder="DESA ..."></div>
                        <div class="col-12"><input type="text" name="kop_alamat" value="<?php echo e(old('kop_alamat', $ps->kop_alamat)); ?>" class="form-control form-control-sm" placeholder="Alamat kantor desa, kode pos"></div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" name="tampilkan_logo" id="tampilkan_logo" value="1" <?php if(old('tampilkan_logo', $ps->tampilkan_logo)): echo 'checked'; endif; ?>>
                                <label class="form-check-label small" for="tampilkan_logo">Tampilkan logo desa di kop <span class="text-muted">(logo diatur di menu Profil &amp; Peta)</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-pen me-1"></i> Penandatangan</h6>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" name="ttd_jabatan" value="<?php echo e(old('ttd_jabatan', $ps->ttd_jabatan)); ?>" class="form-control" placeholder="Kepala Desa Balerejo" required>
                            <div class="form-text">Mis. "Kepala Desa Balerejo", "Lurah ...", atau "a.n. Kepala Desa, Sekretaris Desa".</div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Tempat surat</label>
                            <input type="text" name="tempat_surat" value="<?php echo e(old('tempat_surat', $ps->tempat_surat)); ?>" class="form-control" placeholder="Balerejo">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label">Nama Kepala Desa / Lurah <span class="text-danger">*</span></label>
                            <input type="text" name="ttd_nama" value="<?php echo e(old('ttd_nama', $ps->ttd_nama)); ?>" class="form-control fw-semibold" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">NIP <span class="text-muted fw-normal">(opsional)</span></label>
                            <input type="text" name="ttd_nip" value="<?php echo e(old('ttd_nip', $ps->ttd_nip)); ?>" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-hash me-1"></i> Nomor Surat Otomatis</h6>
                    <input type="text" name="format_nomor" value="<?php echo e(old('format_nomor', $ps->format_nomor)); ?>" class="form-control font-monospace" required>
                    <div class="form-text">
                        Pakai <code>{nomor}</code> (urut, mulai 001 setiap tahun), <code>{kode}</code> (kode jenis surat), <code>{bulan_romawi}</code>, <code>{bulan}</code>, <code>{tahun}</code>.
                        Nomor dibuat otomatis saat surat ditandai <b>Selesai</b> dan nomor dikosongkan.
                        <span class="d-block mt-1">Nomor terakhir tahun <?php echo e($ps->tahun_nomor ?? now()->year); ?>: <b><?php echo e($ps->tahun_nomor ? str_pad($ps->nomor_terakhir, 3, '0', STR_PAD_LEFT) : 'belum ada'); ?></b></span>
                    </div>
                </div>
            </div>

            
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0"><i class="bi bi-text-paragraph me-1"></i> Isi Surat</h6>
                        <span class="small text-muted">Urutan di surat: pembuka → data warga → isi → penutup</span>
                    </div>
                    <label class="form-label">Kalimat pembuka</label>
                    <textarea name="kalimat_pembuka" rows="3" class="form-control mb-3 template-teks"><?php echo e(old('kalimat_pembuka', $ps->kalimat_pembuka)); ?></textarea>

                    <label class="form-label">Isi khusus per jenis surat</label>
                    <select class="form-select form-select-sm mb-2" id="pilihJenis" name="jenis_id">
                        <?php $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($j->id); ?>"><?php echo e($j->nama); ?> (<?php echo e($j->kode); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $j): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <textarea name="isi[<?php echo e($j->id); ?>]" rows="4" class="form-control template-teks isi-jenis" data-jenis="<?php echo e($j->id); ?>" placeholder="Kosongkan untuk memakai isi bawaan di bawah"><?php echo e(old('isi.' . $j->id, $j->isi_surat)); ?></textarea>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <label class="form-label mt-3">Isi bawaan <span class="text-muted fw-normal">(untuk jenis surat yang isi khususnya kosong)</span></label>
                    <textarea name="isi_bawaan" rows="3" class="form-control mb-3 template-teks"><?php echo e(old('isi_bawaan', $ps->isi_bawaan)); ?></textarea>

                    <label class="form-label">Kalimat penutup</label>
                    <textarea name="kalimat_penutup" rows="2" class="form-control template-teks"><?php echo e(old('kalimat_penutup', $ps->kalimat_penutup)); ?></textarea>

                    <div class="kata-kunci mt-3">
                        <div class="small fw-semibold mb-2"><i class="bi bi-magic me-1"></i> Kata kunci (klik untuk menyisipkan) · <code>**teks**</code> = cetak tebal</div>
                        <?php $__currentLoopData = $kataKunci; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $ket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="kk-chip" data-kk="<?php echo e($k); ?>" title="<?php echo e($ket); ?>"><?php echo e($k); ?></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-biru px-4"><i class="bi bi-save me-1"></i> Simpan Pengaturan</button>
                <button type="submit" class="btn btn-outline-secondary" formaction="<?php echo e(route('admin.surat.pengaturan.pratinjau')); ?>" formtarget="_blank" name="pdf" value="1" formnovalidate>
                    <i class="bi bi-filetype-pdf me-1"></i> Lihat sebagai PDF
                </button>
                <button type="submit" class="btn btn-link text-muted ms-auto" form="formReset" onclick="return confirm('Kembalikan kop & kalimat ke bawaan dari Profil Desa?')">Kembalikan ke bawaan</button>
            </div>
        </div>

        
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
<form action="<?php echo e(route('admin.surat.pengaturan.reset')); ?>" method="POST" id="formReset"><?php echo csrf_field(); ?></form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        var form = document.getElementById('formSurat'), frame = document.getElementById('pratinjauFrame');
        var status = document.getElementById('statusPratinjau'), pilih = document.getElementById('pilihJenis');
        var urlPratinjau = <?php echo json_encode(route('admin.surat.pengaturan.pratinjau'), 15, 512) ?>;
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\surat\pengaturan.blade.php ENDPATH**/ ?>