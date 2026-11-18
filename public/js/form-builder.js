/* =========================================================
   SIPADES — Dynamic Form Builder (admin)
   Menyusun isian formulir secara visual dan menyimpannya sebagai skema JSON.
   ========================================================= */
(function () {
    'use strict';

    var TYPES = {
        text:     { label: 'Teks Singkat',  icon: 'bi-input-cursor-text' },
        textarea: { label: 'Teks Panjang',  icon: 'bi-textarea-t' },
        number:   { label: 'Angka',         icon: 'bi-123' },
        nik:      { label: 'NIK',           icon: 'bi-person-vcard' },
        email:    { label: 'Email',         icon: 'bi-envelope' },
        tel:      { label: 'No. Telepon',   icon: 'bi-telephone' },
        date:     { label: 'Tanggal',       icon: 'bi-calendar-date' },
        select:   { label: 'Dropdown',      icon: 'bi-menu-button-wide' },
        radio:    { label: 'Pilihan Ganda', icon: 'bi-ui-radios' },
        checkbox: { label: 'Kotak Centang', icon: 'bi-ui-checks' },
        section:  { label: 'Judul Bagian',  icon: 'bi-type-h3' }
    };
    var CHOICE = ['select', 'radio', 'checkbox'];

    var TEMPLATES = {
        bansos: [
            { type: 'section', label: 'Data Kepala Keluarga' },
            { type: 'text', label: 'Nama Kepala Keluarga', required: true },
            { type: 'nik', label: 'NIK Kepala Keluarga', required: true, width: 'half' },
            { type: 'tel', label: 'No. HP / WhatsApp', width: 'half' },
            { type: 'number', label: 'Jumlah Anggota Keluarga', required: true, min: 1, max: 30, width: 'half' },
            { type: 'select', label: 'Pekerjaan Utama', required: true, width: 'half', options: ['Petani', 'Buruh Harian', 'Pedagang', 'Nelayan', 'Tidak Bekerja', 'Lainnya'] },
            { type: 'section', label: 'Kondisi Rumah' },
            { type: 'radio', label: 'Status Kepemilikan Rumah', required: true, options: ['Milik Sendiri', 'Sewa / Kontrak', 'Menumpang'] },
            { type: 'checkbox', label: 'Bantuan yang Pernah Diterima', options: ['PKH', 'BPNT', 'BLT Dana Desa', 'Belum Pernah'] },
            { type: 'textarea', label: 'Keterangan Tambahan', placeholder: 'Ceritakan kondisi keluarga bila perlu' }
        ],
        usaha: [
            { type: 'section', label: 'Data Usaha' },
            { type: 'text', label: 'Nama Usaha', required: true },
            { type: 'select', label: 'Bidang Usaha', required: true, width: 'half', options: ['Kuliner', 'Perdagangan', 'Pertanian', 'Kerajinan', 'Jasa', 'Lainnya'] },
            { type: 'date', label: 'Mulai Usaha Sejak', width: 'half' },
            { type: 'textarea', label: 'Alamat Tempat Usaha', required: true },
            { type: 'text', label: 'Keperluan Surat', required: true, placeholder: 'Contoh: pengajuan pinjaman modal KUR' }
        ],
        survei: [
            { type: 'radio', label: 'Seberapa puas Anda dengan pelayanan desa?', required: true, options: ['Sangat Puas', 'Puas', 'Cukup', 'Kurang Puas'] },
            { type: 'checkbox', label: 'Layanan yang pernah Anda gunakan', options: ['Surat Menyurat', 'Pengaduan', 'Informasi Berita', 'Data Penduduk'] },
            { type: 'textarea', label: 'Saran untuk Pemerintah Desa' }
        ]
    };

    var form = document.getElementById('formBuilderForm');
    if (!form) return;
    var schemaInput = document.getElementById('schemaInput');
    var list = document.getElementById('fieldList');
    var empty = document.getElementById('emptyState');
    var preview = document.getElementById('preview');
    var countBadge = document.getElementById('fieldCount');
    var uid = 0;
    var openUid = null;
    var fields = [];

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    function make(f) {
        return {
            uid: ++uid,
            type: f.type,
            label: f.label || TYPES[f.type].label,
            name: f.name || '',
            required: !!f.required,
            placeholder: f.placeholder || '',
            help: f.help || '',
            width: f.width === 'half' ? 'half' : 'full',
            options: CHOICE.indexOf(f.type) > -1 ? (f.options && f.options.length ? f.options.slice() : ['Pilihan 1', 'Pilihan 2']) : [],
            min: f.min != null ? f.min : '',
            max: f.max != null ? f.max : ''
        };
    }

    // ---------- Muat skema awal ----------
    try {
        var initial = JSON.parse(schemaInput.value || '{}');
        (initial.fields || []).forEach(function (f) { if (TYPES[f.type]) fields.push(make(f)); });
    } catch (e) { /* skema rusak: mulai kosong */ }

    // ---------- Simpan ke input tersembunyi ----------
    function sync() {
        schemaInput.value = JSON.stringify({
            fields: fields.map(function (f) {
                var o = { type: f.type, label: f.label, name: f.name, help: f.help };
                if (f.type !== 'section') { o.required = f.required; o.placeholder = f.placeholder; o.width = f.width; }
                if (CHOICE.indexOf(f.type) > -1) o.options = f.options;
                if (f.type === 'number') { o.min = f.min === '' ? null : Number(f.min); o.max = f.max === '' ? null : Number(f.max); }
                return o;
            })
        });
        var n = fields.filter(function (f) { return f.type !== 'section'; }).length;
        countBadge.textContent = n + ' isian';
        empty.style.display = fields.length ? 'none' : '';
    }

    // ---------- Kartu editor ----------
    function renderList() {
        list.innerHTML = fields.map(function (f, i) {
            var t = TYPES[f.type];
            var open = openUid === f.uid;
            var body = '';
            if (open) {
                body += '<div class="fc-body row g-2">';
                body += '<div class="col-12"><label class="form-label small">' + (f.type === 'section' ? 'Teks Judul' : 'Pertanyaan / Label') + '</label>' +
                        '<input class="form-control form-control-sm" data-k="label" value="' + esc(f.label) + '"></div>';
                if (f.type !== 'section') {
                    body += '<div class="col-md-6"><label class="form-label small">Contoh isian (placeholder)</label>' +
                            '<input class="form-control form-control-sm" data-k="placeholder" value="' + esc(f.placeholder) + '"></div>';
                    body += '<div class="col-md-6"><label class="form-label small">Lebar</label><select class="form-select form-select-sm" data-k="width">' +
                            '<option value="full"' + (f.width === 'full' ? ' selected' : '') + '>Penuh</option>' +
                            '<option value="half"' + (f.width === 'half' ? ' selected' : '') + '>Setengah</option></select></div>';
                }
                if (CHOICE.indexOf(f.type) > -1) {
                    body += '<div class="col-12"><label class="form-label small">Pilihan (satu per baris)</label>' +
                            '<textarea class="form-control form-control-sm" rows="4" data-k="options">' + esc(f.options.join('\n')) + '</textarea></div>';
                }
                if (f.type === 'number') {
                    body += '<div class="col-6"><label class="form-label small">Nilai minimum</label><input type="number" class="form-control form-control-sm" data-k="min" value="' + esc(f.min) + '"></div>';
                    body += '<div class="col-6"><label class="form-label small">Nilai maksimum</label><input type="number" class="form-control form-control-sm" data-k="max" value="' + esc(f.max) + '"></div>';
                }
                body += '<div class="col-12"><label class="form-label small">Keterangan bantuan</label>' +
                        '<input class="form-control form-control-sm" data-k="help" value="' + esc(f.help) + '" placeholder="Opsional"></div>';
                if (f.type !== 'section') {
                    body += '<div class="col-12"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" data-k="required" id="req' + f.uid + '"' + (f.required ? ' checked' : '') + '>' +
                            '<label class="form-check-label small fw-semibold" for="req' + f.uid + '">Wajib diisi</label></div></div>';
                }
                body += '</div>';
            }
            return '<div class="field-card' + (open ? ' open' : '') + (f.type === 'section' ? ' is-section' : '') + '" draggable="true" data-uid="' + f.uid + '">' +
                '<div class="fc-head">' +
                    '<span class="fc-drag" title="Seret untuk memindah"><i class="bi bi-grip-vertical"></i></span>' +
                    '<span class="fc-icon"><i class="bi ' + t.icon + '"></i></span>' +
                    '<span class="fc-title"><span class="fc-label">' + esc(f.label) + (f.required ? ' <b class="text-danger">*</b>' : '') + '</span>' +
                    '<span class="fc-type">' + t.label + (f.width === 'half' && f.type !== 'section' ? ' · setengah' : '') + '</span></span>' +
                    '<span class="fc-actions">' +
                        '<button type="button" data-act="up" title="Naik"' + (i === 0 ? ' disabled' : '') + '><i class="bi bi-arrow-up"></i></button>' +
                        '<button type="button" data-act="down" title="Turun"' + (i === fields.length - 1 ? ' disabled' : '') + '><i class="bi bi-arrow-down"></i></button>' +
                        '<button type="button" data-act="copy" title="Duplikat"><i class="bi bi-copy"></i></button>' +
                        '<button type="button" data-act="del" title="Hapus" class="del"><i class="bi bi-trash"></i></button>' +
                        '<button type="button" data-act="toggle" title="Ubah"><i class="bi bi-chevron-' + (open ? 'up' : 'down') + '"></i></button>' +
                    '</span>' +
                '</div>' + body + '</div>';
        }).join('');
        renderPreview();
        sync();
    }

    // ---------- Pratinjau ----------
    function renderPreview() {
        var judul = form.querySelector('[name=judul]').value.trim();
        var desk = form.querySelector('[name=deskripsi]').value.trim();
        document.getElementById('previewTitle').textContent = judul || 'Judul Formulir';
        document.getElementById('previewDesc').textContent = desk;

        if (!fields.length) { preview.innerHTML = '<div class="col-12 text-center text-muted small py-4">Pratinjau akan muncul di sini.</div>'; return; }
        preview.innerHTML = fields.map(function (f) {
            if (f.type === 'section') {
                return '<div class="col-12"><div class="form-section-title">' + esc(f.label) + '</div>' + (f.help ? '<div class="form-text mt-0">' + esc(f.help) + '</div>' : '') + '</div>';
            }
            var req = f.required ? ' <span class="text-danger">*</span>' : '';
            var ph = esc(f.placeholder);
            var input;
            switch (f.type) {
                case 'textarea': input = '<textarea class="form-control" rows="3" placeholder="' + ph + '" disabled></textarea>'; break;
                case 'select': input = '<select class="form-select" disabled><option>' + (ph || 'Pilih...') + '</option></select>'; break;
                case 'radio': case 'checkbox':
                    input = f.options.map(function (o) {
                        return '<div class="form-check"><input class="form-check-input" type="' + f.type + '" disabled><label class="form-check-label">' + esc(o) + '</label></div>';
                    }).join('');
                    break;
                case 'date': input = '<input type="date" class="form-control" disabled>'; break;
                case 'nik': input = '<input class="form-control" placeholder="' + (ph || '16 digit NIK') + '" disabled>'; break;
                default: input = '<input class="form-control" placeholder="' + ph + '" disabled>';
            }
            return '<div class="' + (f.width === 'half' ? 'col-md-6' : 'col-12') + '"><label class="form-label">' + esc(f.label) + req + '</label>' + input +
                   (f.help ? '<div class="form-text">' + esc(f.help) + '</div>' : '') + '</div>';
        }).join('');
    }

    function find(uidVal) { for (var i = 0; i < fields.length; i++) if (fields[i].uid === uidVal) return i; return -1; }

    // ---------- Aksi ----------
    document.getElementById('palette').addEventListener('click', function (e) {
        var b = e.target.closest('.palette-item'); if (!b) return;
        var f = make({ type: b.dataset.type });
        fields.push(f);
        openUid = f.uid;
        renderList();
        var card = list.querySelector('[data-uid="' + f.uid + '"]');
        if (card) { card.classList.add('just-added'); card.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); var inp = card.querySelector('[data-k=label]'); if (inp) { inp.focus(); inp.select(); } }
    });

    list.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-act]');
        var card = e.target.closest('.field-card'); if (!card) return;
        var i = find(+card.dataset.uid);
        if (!btn) { if (e.target.closest('.fc-head')) { openUid = openUid === fields[i].uid ? null : fields[i].uid; renderList(); } return; }
        var act = btn.dataset.act;
        if (act === 'up' && i > 0) fields.splice(i - 1, 0, fields.splice(i, 1)[0]);
        if (act === 'down' && i < fields.length - 1) fields.splice(i + 1, 0, fields.splice(i, 1)[0]);
        if (act === 'copy') { var c = make(JSON.parse(JSON.stringify(fields[i]))); c.name = ''; c.label += ' (salinan)'; fields.splice(i + 1, 0, c); openUid = c.uid; }
        if (act === 'del') { if (!confirm('Hapus isian "' + fields[i].label + '"?')) return; fields.splice(i, 1); }
        if (act === 'toggle') openUid = openUid === fields[i].uid ? null : fields[i].uid;
        renderList();
    });

    // ubah properti tanpa render ulang daftar (agar kursor tidak hilang)
    list.addEventListener('input', function (e) {
        var el = e.target, k = el.dataset.k; if (!k) return;
        var card = el.closest('.field-card'); var f = fields[find(+card.dataset.uid)];
        if (k === 'required') f.required = el.checked;
        else if (k === 'options') f.options = el.value.split('\n').map(function (s) { return s.trim(); }).filter(Boolean);
        else f[k] = el.value;
        if (k === 'label' || k === 'required' || k === 'width') {
            card.querySelector('.fc-label').innerHTML = esc(f.label) + (f.required ? ' <b class="text-danger">*</b>' : '');
            card.querySelector('.fc-type').textContent = TYPES[f.type].label + (f.width === 'half' && f.type !== 'section' ? ' · setengah' : '');
        }
        renderPreview(); sync();
    });

    // ---------- Seret untuk mengurutkan ----------
    var dragUid = null;
    list.addEventListener('dragstart', function (e) {
        var card = e.target.closest('.field-card');
        if (!card || e.target.closest('input, textarea, select')) { e.preventDefault(); return; }
        dragUid = +card.dataset.uid; card.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', String(dragUid)); } catch (x) {}
    });
    list.addEventListener('dragover', function (e) {
        if (dragUid === null) return; e.preventDefault();
        var over = e.target.closest('.field-card'); if (!over || +over.dataset.uid === dragUid) return;
        var from = find(dragUid), to = find(+over.dataset.uid);
        var rect = over.getBoundingClientRect(); var after = e.clientY > rect.top + rect.height / 2;
        var item = fields.splice(from, 1)[0];
        to = find(+over.dataset.uid); fields.splice(after ? to + 1 : to, 0, item);
        var dragEl = list.querySelector('[data-uid="' + dragUid + '"]');
        over.parentNode.insertBefore(dragEl, after ? over.nextSibling : over);
    });
    list.addEventListener('dragend', function () { dragUid = null; renderList(); });

    // ---------- Template ----------
    document.querySelectorAll('[data-template]').forEach(function (a) {
        a.addEventListener('click', function (e) {
            e.preventDefault();
            if (fields.length && !confirm('Ganti isian yang ada dengan template?')) return;
            fields = TEMPLATES[a.dataset.template].map(make); openUid = null;
            if (a.dataset.template === 'usaha') { var k = document.getElementById('kategori'); k.value = 'surat'; toggle(); }
            renderList();
        });
    });

    // ---------- Tampilkan / sembunyikan bagian sesuai jenis ----------
    function toggle() {
        var tipe = (form.querySelector('[name=tipe]:checked') || {}).value;
        var kat = document.getElementById('kategori').value;
        form.querySelectorAll('[data-show]').forEach(function (el) {
            var s = el.dataset.show;
            var show = s === 'tautan' ? tipe === 'tautan' : s === 'dinamis' ? tipe === 'dinamis' : (tipe === 'dinamis' && kat === 'surat');
            el.style.display = show ? '' : 'none';
        });
        var login = document.getElementById('butuh_login');
        if (tipe === 'dinamis' && kat === 'surat') { login.checked = true; login.disabled = true; } else { login.disabled = false; }
    }
    form.querySelectorAll('[name=tipe]').forEach(function (r) { r.addEventListener('change', toggle); });
    document.getElementById('kategori').addEventListener('change', toggle);
    form.querySelector('[name=judul]').addEventListener('input', renderPreview);
    form.querySelector('[name=deskripsi]').addEventListener('input', renderPreview);

    form.addEventListener('submit', function (e) {
        var tipe = (form.querySelector('[name=tipe]:checked') || {}).value;
        document.getElementById('butuh_login').disabled = false; // agar ikut terkirim
        if (tipe === 'dinamis' && !fields.some(function (f) { return f.type !== 'section'; })) {
            e.preventDefault(); alert('Tambahkan minimal satu isian pada formulir.');
        }
        sync();
    });

    toggle();
    renderList();
})();
