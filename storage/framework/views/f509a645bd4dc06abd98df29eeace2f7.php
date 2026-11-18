<?php ($isEdit = $lokasi->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Lokasi' : 'Tambah Lokasi'); ?>
<?php $__env->startSection('subtitle', 'Tandai fasilitas umum desa di peta'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($isEdit ? route('admin.lokasi.update', $lokasi) : route('admin.lokasi.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger small"><i class="bi bi-exclamation-triangle me-1"></i> <?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="nama" value="<?php echo e(old('nama', $lokasi->nama)); ?>" class="form-control" placeholder="Contoh: Masjid Al-Ikhlas" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori <span class="text-danger">*</span></label>
                        <div class="kategori-pilih">
                            <?php $__currentLoopData = \App\Models\LokasiDesa::KATEGORI; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => [$label, $ikon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <input type="radio" class="btn-check" name="kategori" id="kat_<?php echo e($k); ?>" value="<?php echo e($k); ?>" data-ikon="<?php echo e($ikon); ?>" <?php if(old('kategori', $lokasi->kategori) === $k): echo 'checked'; endif; ?>>
                                <label class="kat-btn" for="kat_<?php echo e($k); ?>"><i class="bi <?php echo e($ikon); ?>"></i> <?php echo e($label); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat / patokan</label>
                        <input type="text" name="alamat" value="<?php echo e(old('alamat', $lokasi->alamat)); ?>" class="form-control" placeholder="Contoh: RT 02/RW 01, depan lapangan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Jam buka, layanan, dll. (opsional)"><?php echo e(old('keterangan', $lokasi->keterangan)); ?></textarea>
                    </div>

                    <label class="form-label">Titik Koordinat <span class="text-danger">*</span></label>
                    <div class="row g-2 mb-2">
                        <div class="col-6"><input type="text" inputmode="decimal" name="lat" id="lat" value="<?php echo e(old('lat', $lokasi->lat)); ?>" class="form-control form-control-sm font-monospace" placeholder="Lintang (lat)" required></div>
                        <div class="col-6"><input type="text" inputmode="decimal" name="lng" id="lng" value="<?php echo e(old('lng', $lokasi->lng)); ?>" class="form-control form-control-sm font-monospace" placeholder="Bujur (lng)" required></div>
                    </div>
                    <div class="input-group input-group-sm mb-2">
                        <span class="input-group-text"><i class="bi bi-clipboard"></i></span>
                        <input type="text" id="tempelKoordinat" class="form-control" placeholder="Tempel dari Google Maps, mis. -8.0609, 111.8730">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100 mb-3" id="lokasiSaya"><i class="bi bi-crosshair me-1"></i> Gunakan lokasi saya sekarang</button>

                    <div class="mb-3">
                        <label class="form-label">Foto <span class="text-muted fw-normal">(opsional — otomatis dikompres)</span></label>
                        <?php if($lokasi->foto): ?>
                            <img src="<?php echo e($lokasi->foto_url); ?>" alt="" class="d-block rounded-3 mb-2" style="width: 100%; max-height: 160px; object-fit: cover;">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="hapus_foto" value="1" id="hapusFoto">
                                <label class="form-check-label small text-danger" for="hapusFoto">Hapus foto</label>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="foto" accept="image/*" class="form-control form-control-sm">
                    </div>

                    <div class="d-flex flex-wrap gap-4 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_utama" id="is_utama" value="1" <?php if(old('is_utama', $lokasi->is_utama)): echo 'checked'; endif; ?>>
                            <label class="form-check-label small fw-semibold" for="is_utama">Titik utama (pusat peta)</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" <?php if(old('is_aktif', $lokasi->is_aktif ?? true)): echo 'checked'; endif; ?>>
                            <label class="form-check-label small fw-semibold" for="is_aktif">Tampilkan ke warga</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-biru px-4"><i class="bi bi-save me-1"></i> Simpan</button>
                        <a href="<?php echo e(route('admin.lokasi.index')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card peta-admin">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-hand-index-thumb me-1"></i> Klik peta untuk menandai lokasi</span>
                    <span class="small text-muted">Penanda bisa digeser</span>
                </div>
                <div id="petaForm" class="peta-kanvas"></div>
            </div>
            <div class="small text-muted mt-2"><i class="bi bi-shield-check me-1"></i> Hanya untuk fasilitas umum. Jangan menandai rumah pribadi warga.</div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="<?php echo e(asset('js/peta-desa.js')); ?>?v=1"></script>
<script>
    (function () {
        var lat = document.getElementById('lat'), lng = document.getElementById('lng');
        var awal = (lat.value && lng.value) ? [parseFloat(lat.value), parseFloat(lng.value)] : <?php echo json_encode($pusat, 15, 512) ?>;
        var peta = PetaDesa.buat('petaForm', awal, 17);
        peta.scrollWheelZoom.enable();

        // lokasi lain sebagai patokan (samar)
        <?php echo json_encode($semua, 15, 512) ?>.forEach(function (l) {
            L.marker([l.lat, l.lng], { icon: PetaDesa.ikon(l.ikon, l.utama), opacity: .45, interactive: true })
                .bindTooltip(PetaDesa.esc(l.nama)).addTo(peta);
        });

        function ikonKat() { var r = document.querySelector('[name=kategori]:checked'); return r ? r.dataset.ikon : 'bi-geo-alt-fill'; }
        var penanda = null;
        function tandai(p, geser) {
            lat.value = (+p.lat).toFixed(7); lng.value = (+p.lng).toFixed(7);
            if (!penanda) {
                penanda = L.marker(p, { draggable: true, icon: PetaDesa.ikon(ikonKat(), document.getElementById('is_utama').checked, true) }).addTo(peta);
                penanda.on('dragend', function () { var q = penanda.getLatLng(); lat.value = q.lat.toFixed(7); lng.value = q.lng.toFixed(7); });
            } else penanda.setLatLng(p);
            if (geser) peta.setView(p, Math.max(peta.getZoom(), 17));
        }
        if (lat.value && lng.value) tandai({ lat: lat.value, lng: lng.value });

        peta.on('click', function (e) { tandai(e.latlng); });
        [lat, lng].forEach(function (i) { i.addEventListener('change', function () { if (lat.value && lng.value && !isNaN(lat.value) && !isNaN(lng.value)) tandai({ lat: lat.value, lng: lng.value }, true); }); });
        document.querySelectorAll('[name=kategori], #is_utama').forEach(function (r) {
            r.addEventListener('change', function () { if (penanda) penanda.setIcon(PetaDesa.ikon(ikonKat(), document.getElementById('is_utama').checked, true)); });
        });

        // tempel koordinat dari Google Maps ("-8.06, 111.87")
        document.getElementById('tempelKoordinat').addEventListener('input', function () {
            var m = this.value.match(/(-?\d+(?:\.\d+)?)\s*[,;\s]\s*(-?\d+(?:\.\d+)?)/);
            if (m && Math.abs(m[1]) <= 90 && Math.abs(m[2]) <= 180) { tandai({ lat: m[1], lng: m[2] }, true); this.classList.add('is-valid'); }
            else this.classList.remove('is-valid');
        });

        // lokasi perangkat (mis. admin sedang berada di depan fasilitas)
        document.getElementById('lokasiSaya').addEventListener('click', function () {
            var b = this;
            if (!navigator.geolocation) { alert('Perangkat tidak mendukung lokasi.'); return; }
            b.disabled = true; b.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mencari lokasi…';
            navigator.geolocation.getCurrentPosition(function (pos) {
                tandai({ lat: pos.coords.latitude, lng: pos.coords.longitude }, true);
                b.disabled = false; b.innerHTML = '<i class="bi bi-check2 me-1"></i> Lokasi dipakai (akurasi ±' + Math.round(pos.coords.accuracy) + ' m)';
            }, function () {
                b.disabled = false; b.innerHTML = '<i class="bi bi-crosshair me-1"></i> Gunakan lokasi saya sekarang';
                alert('Lokasi tidak dapat diambil. Izinkan akses lokasi di browser, atau klik langsung di peta.');
            }, { enableHighAccuracy: true, timeout: 15000 });
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\lokasi\form.blade.php ENDPATH**/ ?>