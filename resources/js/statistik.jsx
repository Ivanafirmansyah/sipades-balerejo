/**
 * SIPADES — titik masuk grafik statistik (React "islands").
 * Halaman Blade cukup menaruh:  <div data-grafik="wilayah" data-props='{"data":[...]}'></div>
 * lalu komponen grafik shadcn/ui dipasang di elemen tersebut. Bagian lain halaman tetap Blade + Bootstrap.
 */
import { createRoot } from 'react-dom/client';
import GrafikWilayah from '@/grafik/GrafikWilayah';
import GrafikJenisKelamin from '@/grafik/GrafikJenisKelamin';
import GrafikUmur from '@/grafik/GrafikUmur';
import GrafikPeringkat from '@/grafik/GrafikPeringkat';

const KOMPONEN = {
    wilayah: GrafikWilayah,
    'jenis-kelamin': GrafikJenisKelamin,
    umur: GrafikUmur,
    peringkat: GrafikPeringkat,
};

function pasang() {
    document.querySelectorAll('[data-grafik]:not([data-terpasang])').forEach((el) => {
        const Komponen = KOMPONEN[el.dataset.grafik];
        if (!Komponen) return;
        let props = {};
        try {
            props = JSON.parse(el.dataset.props || '{}');
        } catch (e) {
            console.error('Data grafik tidak valid', e);
        }
        el.dataset.terpasang = '1';
        el.innerHTML = '';
        createRoot(el).render(<Komponen {...props} />);
    });
}

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', pasang);
else pasang();
