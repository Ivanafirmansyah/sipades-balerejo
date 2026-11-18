<?php ($isEdit = $formulir->exists); ?>
<?php $__env->startSection('title', $isEdit ? 'Edit Formulir' : 'Buat Formulir'); ?>
<?php $__env->startSection('subtitle', 'Susun formulir sendiri tanpa mengubah kode program'); ?>

<?php $__env->startSection('sidebar'); ?>
    <?php echo $__env->make('partials.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($isEdit ? route('admin.formulir.update', $formulir) : route('admin.formulir.store')); ?>" method="POST" id="formBuilderForm">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>
    <input type="hidden" name="schema" id="schemaInput" value="<?php echo e(old('schema', json_encode($formulir->schema ?? ['fields' => []]))); ?>">

    <?php if($errors->any()): ?>
        <div class="alert alert-danger small"><i class="bi bi-exclamation-triangle me-1"></i> <?php echo e($errors->first()); ?></div>
    <?php endif; ?>

    
    <div class="card mb-4">
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-lg-8">
                    <label class="form-label">Judul Formulir <span class="text-danger">*</span></label>
                    <input type="text" name="judul" value="<?php echo e(old('judul', $formulir->judul)); ?>" class="form-control" placeholder="Contoh: Pendataan Penerima Bantuan Sosial 2026" required>
                </div>
                <div class="col-lg-4">
                    <label class="form-label">Jenis Formulir</label>
                    <div class="btn-group w-100 tipe-toggle" role="group">
                        <input type="radio" class="btn-check" name="tipe" id="tipeDinamis" value="dinamis" <?php if(old('tipe', $formulir->tipe) === 'dinamis'): echo 'checked'; endif; ?>>
                        <label class="btn btn-outline-biru" for="tipeDinamis"><i class="bi bi-ui-checks me-1"></i> Dinamis</label>
                        <input type="radio" class="btn-check" name="tipe" id="tipeTautan" value="tautan" <?php if(old('tipe', $formulir->tipe) === 'tautan'): echo 'checked'; endif; ?>>
                        <label class="btn btn-outline-biru" for="tipeTautan"><i class="bi bi-link-45deg me-1"></i> Tautan Luar</label>
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="2" class="form-control" placeholder="Penjelasan singkat untuk warga"><?php echo e(old('deskripsi', $formulir->deskripsi)); ?></textarea>
                </div>

                
                <div class="col-12" data-show="tautan">
                    <label class="form-label">Link Formulir <span class="text-danger">*</span></label>
                    <input type="url" name="link" value="<?php echo e(old('link', $formulir->link)); ?>" class="form-control" placeholder="https://forms.gle/...">
                </div>

                
                <div class="col-md-4" data-show="dinamis">
                    <label class="form-label">Kegunaan</label>
                    <select name="kategori" id="kategori" class="form-select">
                        <option value="pendataan" <?php if(old('kategori', $formulir->kategori) === 'pendataan'): echo 'selected'; endif; ?>>Pendataan / Survei</option>
                        <option value="surat" <?php if(old('kategori', $formulir->kategori) === 'surat'): echo 'selected'; endif; ?>>Pengajuan Surat</option>
                    </select>
                </div>
                <div class="col-md-4" data-show="surat">
                    <label class="form-label">Jenis Surat <span class="text-danger">*</span></label>
                    <select name="jenis_surat_id" class="form-select">
                        <option value="">Pilih jenis surat</option>
                        <?php $__currentLoopData = $jenisSurats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $js): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($js->id); ?>" <?php if(old('jenis_surat_id', $formulir->jenis_surat_id) == $js->id): echo 'selected'; endif; ?>><?php echo e($js->nama); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="form-text">Isian otomatis masuk ke menu Surat Menyurat.</div>
                </div>
                <div class="col-md-4" data-show="dinamis">
                    <label class="form-label">Pesan setelah terkirim</label>
                    <input type="text" name="pesan_sukses" value="<?php echo e(old('pesan_sukses', $formulir->pesan_sukses)); ?>" class="form-control" placeholder="Terima kasih, data Anda sudah kami terima.">
                </div>
                <div class="col-12 d-flex flex-wrap gap-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_aktif" id="is_aktif" value="1" <?php if(old('is_aktif', $formulir->is_aktif ?? true)): echo 'checked'; endif; ?>>
                        <label class="form-check-label small fw-semibold" for="is_aktif">Tampilkan ke warga</label>
                    </div>
                    <div class="form-check form-switch" data-show="dinamis">
                        <input class="form-check-input" type="checkbox" name="butuh_login" id="butuh_login" value="1" <?php if(old('butuh_login', $formulir->butuh_login)): echo 'checked'; endif; ?>>
                        <label class="form-check-label small fw-semibold" for="butuh_login">Wajib login untuk mengisi <span class="text-muted fw-normal" data-show="surat">(otomatis untuk surat)</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="builder" data-show="dinamis">
        <div class="row g-4">
            <div class="col-xl-7">
                <div class="card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h6 class="fw-bold mb-0"><i class="bi bi-tools me-1"></i> Susun Isian</h6>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-magic me-1"></i> Pakai Template</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#" data-template="bansos"><i class="bi bi-people"></i> Pendataan Bantuan Sosial</a></li>
                                    <li><a class="dropdown-item" href="#" data-template="usaha"><i class="bi bi-shop"></i> Surat Keterangan Usaha</a></li>
                                    <li><a class="dropdown-item" href="#" data-template="survei"><i class="bi bi-emoji-smile"></i> Survei Kepuasan Layanan</a></li>
                                </ul>
                            </div>
                        </div>

                        <div class="palette mb-3" id="palette">
                            <?php $__currentLoopData = \App\Support\FormSchema::TYPES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="palette-item" data-type="<?php echo e($type); ?>"><i class="bi <?php echo e(['text'=>'bi-input-cursor-text','textarea'=>'bi-textarea-t','number'=>'bi-123','nik'=>'bi-person-vcard','email'=>'bi-envelope','tel'=>'bi-telephone','date'=>'bi-calendar-date','select'=>'bi-menu-button-wide','radio'=>'bi-ui-radios','checkbox'=>'bi-ui-checks','section'=>'bi-type-h3'][$type]); ?>"></i><span><?php echo e($label); ?></span></button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div id="fieldList" class="field-list"></div>
                        <div id="emptyState" class="builder-empty">
                            <i class="bi bi-hand-index-thumb"></i>
                            <div class="fw-bold">Belum ada isian</div>
                            <div class="small">Klik jenis isian di atas, atau pilih template.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card preview-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-phone me-1"></i> Pratinjau untuk Warga</span>
                        <span class="badge badge-soft-info" id="fieldCount">0 isian</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="preview-title fw-bold mb-1" id="previewTitle">Judul Formulir</div>
                        <div class="small text-muted mb-3" id="previewDesc"></div>
                        <div class="row g-3" id="preview"></div>
                    </div>
                </div>
                <div class="small text-muted mt-2"><i class="bi bi-info-circle me-1"></i> Skema disimpan sebagai JSON. Isian dapat dibuka dan diisi warga meskipun tanpa sinyal.</div>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4">
        <button class="btn btn-biru px-4"><i class="bi bi-save me-1"></i> Simpan Formulir</button>
        <a href="<?php echo e(route('admin.formulir.index')); ?>" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/form-builder.js')); ?>?v=1"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\laragon\www\sipades\resources\views\admin\formulir\form.blade.php ENDPATH**/ ?>