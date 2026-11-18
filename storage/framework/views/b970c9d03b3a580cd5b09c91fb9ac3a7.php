

<?php $__env->startSection('title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<?php ($foto = 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=1600'); ?>


<section id="heroSlider" class="carousel slide carousel-fade hero-slider" data-bs-ride="carousel" data-bs-interval="6000">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <div class="carousel-item active" style="background-image: url('<?php echo e($foto); ?>'); background-position: center 40%;">
            <div class="container hero-content">
                <div class="hero-tagline">Mudah, Cepat, dan Praktis</div>
                <h1 class="hero-title">Layanan Surat Online 24 Jam</h1>
                <p class="hero-text">Ajukan surat keterangan kapan saja dan di mana saja tanpa harus antre di kantor desa. Pantau status pengajuan langsung dari dashboard warga.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('layanan')); ?>" class="btn btn-kuning rounded-pill px-4">Ajukan Surat <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="<?php echo e(route('layanan')); ?>#pengaduan" class="btn btn-outline-light rounded-pill px-4">Kirim Pengaduan</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('<?php echo e($foto); ?>'); background-position: center 80%;">
            <div class="container hero-content">
                <div class="hero-tagline">Website Resmi</div>
                <h1 class="hero-title">Selamat Datang di <?php echo e($profil->nama_desa); ?></h1>
                <p class="hero-text">Pusat informasi dan layanan administrasi desa yang modern, transparan, dan mudah diakses oleh seluruh masyarakat.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('profil')); ?>" class="btn btn-light rounded-pill px-4">Profil Desa <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-light rounded-pill px-4">Portal Warga</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="background-image: url('<?php echo e($foto); ?>'); background-position: center 10%;">
            <div class="container hero-content">
                <div class="hero-tagline">Transparan & Terbuka</div>
                <h1 class="hero-title">Informasi Desa Dalam Genggaman</h1>
                <p class="hero-text">Ikuti berita, pengumuman, agenda kegiatan, dan data kependudukan <?php echo e($profil->nama_desa); ?> secara langsung.</p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?php echo e(route('berita.index')); ?>" class="btn btn-kuning rounded-pill px-4">Baca Berita <i class="bi bi-arrow-right btn-arrow"></i></a>
                    <a href="<?php echo e(route('penduduk.publik')); ?>" class="btn btn-outline-light rounded-pill px-4">Data Penduduk</a>
                </div>
            </div>
        </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev"><i class="bi bi-chevron-left"></i></button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next"><i class="bi bi-chevron-right"></i></button>
    <a href="#unggulan" class="hero-scroll" aria-label="Gulir ke bawah"><i class="bi bi-chevron-double-down"></i></a>
</section>


<section id="unggulan" class="band-kuning">
    <div class="container">
        <div class="row g-4">
            <?php $__currentLoopData = [
                ['bi-file-earmark-text', 'Pembuatan Surat Online', 'Ajukan pembuatan surat secara online tanpa harus datang ke kantor desa.', route('layanan'), 'Ajukan Sekarang'],
                ['bi-graph-up-arrow', 'Data & Statistik Desa', 'Akses data demografi dan statistik desa secara transparan dan real-time.', route('penduduk.publik'), 'Lihat Data'],
                ['bi-newspaper', 'Berita & Informasi', 'Dapatkan informasi terkini tentang kegiatan dan program pembangunan desa.', route('berita.index'), 'Baca Berita'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 reveal" style="transition-delay: <?php echo e($i * 120); ?>ms">
                    <a href="<?php echo e($f[3]); ?>" class="feature-card">
                        <div class="feature-media"><i class="bi <?php echo e($f[0]); ?>"></i></div>
                        <div class="feature-body">
                            <h5><?php echo e($f[1]); ?></h5>
                            <p><?php echo e($f[2]); ?></p>
                            <span class="link-kuning"><?php echo e($f[4]); ?> <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-5 my-lg-4">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <h2 class="judul-tengah">Layanan Kami</h2>
            <p class="text-muted mb-0">Berbagai layanan administrasi dan informasi desa yang dapat Anda akses dengan mudah</p>
        </div>
        <div class="row g-4">
            <?php $__currentLoopData = [
                ['bi-envelope-paper', 'Surat Menyurat', 'Pembuatan surat keterangan, surat pengantar, dan dokumen resmi desa lainnya.', route('layanan'), 'Lihat Layanan'],
                ['bi-people', 'Data Penduduk', 'Informasi demografi dan statistik penduduk desa secara transparan.', route('penduduk.publik'), 'Lihat Data'],
                ['bi-megaphone', 'Berita Desa', 'Update terkini tentang kegiatan, program, dan pengumuman desa.', route('berita.index'), 'Baca Berita'],
                ['bi-chat-dots', 'Pengaduan', 'Layanan pengaduan dan aspirasi masyarakat untuk desa yang lebih baik.', route('layanan') . '#pengaduan', 'Hubungi Kami'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-sm-6 col-lg-3 reveal" style="transition-delay: <?php echo e($i * 100); ?>ms">
                    <a href="<?php echo e($l[3]); ?>" class="service-card">
                        <div class="service-icon"><i class="bi <?php echo e($l[0]); ?>"></i></div>
                        <h6><?php echo e($l[1]); ?></h6>
                        <p><?php echo e($l[2]); ?></p>
                        <span class="link-kuning"><?php echo e($l[4]); ?> <i class="bi bi-arrow-right"></i></span>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="quote-section">
    <div class="container text-center reveal">
        <i class="bi bi-quote quote-mark"></i>
        <blockquote>
            <?php echo e($profil->visi ?? 'Pelayanan desa yang modern dan transparan adalah kunci untuk meningkatkan kesejahteraan masyarakat dan membangun desa yang maju.'); ?>

        </blockquote>
        <div class="d-inline-flex align-items-center gap-3 mt-3 text-start">
            <span class="quote-avatar">
                <?php if(!empty($kepalaDesa?->foto)): ?>
                    <img src="<?php echo e(asset('storage/' . $kepalaDesa->foto)); ?>" alt="<?php echo e($kepalaDesa->nama); ?>">
                <?php else: ?>
                    <i class="bi bi-person-fill"></i>
                <?php endif; ?>
            </span>
            <div>
                <div class="fw-bold text-white"><?php echo e($kepalaDesa->nama ?? 'Kepala Desa'); ?></div>
                <div class="small quote-role">Kepala <?php echo e($profil->nama_desa); ?></div>
            </div>
        </div>
    </div>
</section>


<section class="stats-band" style="background-image: url('<?php echo e($foto); ?>');">
    <div class="container">
        <div class="row g-4 text-center">
            <?php $__currentLoopData = [
                [$stats['penduduk'], 'Total Penduduk', 'bi-people-fill'],
                [$stats['kk'], 'Kepala Keluarga', 'bi-house-heart-fill'],
                [$stats['rt'], 'Jumlah RT', 'bi-signpost-2-fill'],
                [$stats['rw'], 'Jumlah RW', 'bi-pin-map-fill'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-6 col-lg-3 reveal">
                    <div class="stat-big">
                        <i class="bi <?php echo e($s[2]); ?>"></i>
                        <div class="angka" data-count="<?php echo e($s[0]); ?>"><?php echo e(number_format($s[0])); ?></div>
                        <div class="label"><?php echo e($s[1]); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="py-5 my-lg-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4 reveal">
            <div>
                <h2 class="judul-kiri">Berita & Pengumuman</h2>
                <p class="text-muted mb-0">Kabar terbaru dari <?php echo e($profil->nama_desa); ?></p>
            </div>
            <a href="<?php echo e(route('berita.index')); ?>" class="btn btn-biru rounded-pill px-4">Lihat Semua <i class="bi bi-arrow-right btn-arrow"></i></a>
        </div>
        <div class="row g-4">
            <?php $__empty_1 = true; $__currentLoopData = $beritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $berita): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-4 reveal" style="transition-delay: <?php echo e($i * 120); ?>ms">
                    <a href="<?php echo e(route('berita.show', $berita->slug)); ?>" class="news-card">
                        <div class="news-img">
                            <?php if($berita->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $berita->gambar)); ?>" alt="<?php echo e($berita->judul); ?>">
                            <?php else: ?>
                                <div class="news-placeholder"><i class="bi bi-newspaper"></i></div>
                            <?php endif; ?>
                            <span class="news-tag"><?php echo e(ucfirst($berita->kategori)); ?></span>
                        </div>
                        <div class="news-body">
                            <div class="news-date"><i class="bi bi-calendar3 me-1"></i><?php echo e(($berita->published_at ?? $berita->created_at)->translatedFormat('d F Y')); ?></div>
                            <h6><?php echo e($berita->judul); ?></h6>
                            <p><?php echo e(\Illuminate\Support\Str::limit(strip_tags($berita->isi), 95)); ?></p>
                            <span class="link-biru">Baca Selengkapnya <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada berita.</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="pb-5 mb-lg-4">
    <div class="container">
        <div class="d-flex flex-wrap align-items-end justify-content-between gap-2 mb-4 reveal">
            <div>
                <h2 class="judul-kiri">Agenda Kegiatan</h2>
                <p class="text-muted mb-0">Jadwal kegiatan desa yang akan datang</p>
            </div>
            <a href="<?php echo e(route('agenda.index')); ?>" class="btn btn-outline-biru rounded-pill px-4">Semua Agenda</a>
        </div>
        <div class="row g-3">
            <?php $__empty_1 = true; $__currentLoopData = $agendas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $agenda): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="col-md-4 reveal" style="transition-delay: <?php echo e($i * 120); ?>ms">
                    <div class="agenda-card">
                        <div class="agenda-date">
                            <div class="tgl"><?php echo e($agenda->tanggal->format('d')); ?></div>
                            <div class="bln"><?php echo e($agenda->tanggal->translatedFormat('M')); ?></div>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-1"><?php echo e($agenda->judul); ?></h6>
                            <div class="small text-muted"><i class="bi bi-clock me-1"></i><?php echo e($agenda->jam_formatted ?? '-'); ?> WIB</div>
                            <div class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo e($agenda->lokasi ?? '-'); ?></div>
                        </div>
                        <?php if($agenda->foto): ?>
                            <img src="<?php echo e($agenda->foto_url); ?>" alt="<?php echo e($agenda->judul); ?>" class="agenda-mini-foto" loading="lazy">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-12"><div class="alert alert-light border text-center text-muted">Belum ada agenda kegiatan.</div></div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    // Animasi angka statistik berhitung naik
    (function () {
        var nums = document.querySelectorAll('.stat-big .angka');
        var run = function (el) {
            var target = +el.dataset.count, start = null;
            var step = function (t) {
                if (!start) start = t;
                var p = Math.min((t - start) / 1200, 1);
                el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
                if (p < 1) requestAnimationFrame(step);
            };
            requestAnimationFrame(step);
        };
        if (!('IntersectionObserver' in window)) return;
        var io = new IntersectionObserver(function (es) {
            es.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
        }, { threshold: .5 });
        nums.forEach(function (n) { io.observe(n); });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\home.blade.php ENDPATH**/ ?>