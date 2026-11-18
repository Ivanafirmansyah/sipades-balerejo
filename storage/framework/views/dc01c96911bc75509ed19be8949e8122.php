
<link rel="manifest" href="<?php echo e(route('pwa.manifest')); ?>">
<meta name="theme-color" content="#0f3a7a">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="<?php echo e(\Illuminate\Support\Str::limit($profil->nama_desa ?? 'SIPADES', 12, '')); ?>">
<link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('icons/icon-192.png')); ?>">
<link rel="apple-touch-icon" href="<?php echo e(asset('icons/apple-touch-icon.png')); ?>">
<?php /**PATH E:\laragon\www\sipades\resources\views\partials\pwa-head.blade.php ENDPATH**/ ?>