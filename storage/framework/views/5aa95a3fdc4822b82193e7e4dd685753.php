<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Masuk'); ?> - SIPADES</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', system-ui, sans-serif; min-height: 100vh; display: grid; place-items: center;
            background: linear-gradient(135deg, #0f5132, #146c43 60%, #1a8754); padding: 1.5rem; }
        .auth-card { width: 100%; max-width: 440px; border: 0; border-radius: 1.1rem; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
        .btn-hijau { background: #146c43; border-color: #146c43; color: #fff; }
        .btn-hijau:hover { background: #0f5132; border-color: #0f5132; color: #fff; }
        .form-control:focus { border-color: #146c43; box-shadow: 0 0 0 .2rem rgba(20,108,67,.15); }
        .brand-icon { width: 64px; height: 64px; display: grid; place-items: center; border-radius: 16px; background: #d1e7dd; color: #146c43; font-size: 1.8rem; }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?php echo e(asset('css/sipades-theme.css')); ?>?v=8" rel="stylesheet">
    <link href="<?php echo e(asset('css/sipades-fitur.css')); ?>?v=2" rel="stylesheet">
</head>
<body class="auth-bg">
    <div class="auth-card card p-4 p-md-5">
        <div class="text-center mb-4">
            <div class="brand-icon mx-auto mb-3"><i class="bi bi-bank2"></i></div>
            <h4 class="fw-bold mb-1"><?php echo $__env->yieldContent('heading', 'SIPADES'); ?></h4>
            <p class="text-muted small mb-0"><?php echo $__env->yieldContent('subheading', 'Sistem Informasi Desa'); ?></p>
        </div>
        <?php echo $__env->make('partials.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH E:\laragon\www\sipades\resources\views/layouts/auth.blade.php ENDPATH**/ ?>