<?php $__env->startSection('title', 'Sedang Offline'); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="container">
        <h1>Sedang Offline</h1>
        <p class="mb-0 opacity-75">Halaman ini belum tersimpan di perangkat Anda</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 text-center">
            <div class="offline-illu"><i class="bi bi-wifi-off"></i></div>
            <h4 class="fw-bold mt-3">Tidak ada sinyal internet</h4>
            <p class="text-muted">Tenang, formulir yang sudah pernah dibuka tetap bisa diisi. Isian akan disimpan di perangkat dan terkirim otomatis begitu sinyal kembali.</p>
            <button type="button" class="btn btn-kuning rounded-pill px-4" onclick="location.reload()"><i class="bi bi-arrow-clockwise me-1"></i> Coba Lagi</button>
        </div>
        <div class="col-lg-7 mt-5">
            <h6 class="fw-bold text-center mb-3">Formulir yang tersedia offline</h6>
            <div class="list-group" id="formOffline">
                <div class="list-group-item text-center text-muted small">Memeriksa penyimpanan perangkat...</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    (function () {
        var box = document.getElementById('formOffline');
        if (!('caches' in window)) { box.innerHTML = '<div class="list-group-item text-center text-muted small">Browser tidak mendukung penyimpanan offline.</div>'; return; }
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (k) { return caches.open(k).then(function (c) { return c.keys().then(function (reqs) { return reqs.map(function (r) { return { cache: c, req: r }; }); }); }); }));
        }).then(function (groups) {
            var seen = {}, items = [];
            [].concat.apply([], groups).forEach(function (x) {
                var u = new URL(x.req.url);
                if (/^\/formulir\/[^/]+$/.test(u.pathname) && !seen[u.pathname]) { seen[u.pathname] = 1; items.push(x); }
            });
            if (!items.length) { box.innerHTML = '<div class="list-group-item text-center text-muted small">Belum ada formulir tersimpan. Buka menu Formulir saat online agar tersimpan otomatis.</div>'; return; }
            return Promise.all(items.map(function (x) {
                return x.cache.match(x.req).then(function (res) { return res.text(); }).then(function (html) {
                    var m = html.match(/<title>([^<]*)<\/title>/i);
                    var t = document.createElement('textarea'); t.innerHTML = m ? m[1].split(' - ')[0] : x.req.url;
                    return { url: new URL(x.req.url).pathname, judul: t.value };
                });
            })).then(function (list) {
                box.innerHTML = '';
                list.forEach(function (f) {
                    var a = document.createElement('a');
                    a.href = f.url; a.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center';
                    a.innerHTML = '<span><i class="bi bi-ui-checks me-2 text-primary"></i></span><span class="badge badge-soft-success"><i class="bi bi-cloud-check"></i> Offline</span>';
                    a.firstChild.appendChild(document.createTextNode(f.judul));
                    box.appendChild(a);
                });
            });
        });
    })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\offline.blade.php ENDPATH**/ ?>