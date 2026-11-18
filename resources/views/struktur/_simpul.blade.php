{{-- Satu simpul (anggota) pada bagan pohon, beserta cabang bawahannya (rekursif) --}}
<li style="--tunda: {{ $level * 140 }}ms">
    <div class="simpul-wadah">
        <div class="simpul {{ $level === 0 ? 'simpul-akar' : '' }}" tabindex="0">
            <span class="simpul-foto">
                @if ($p->foto)
                    <img src="{{ $p->foto_url }}" alt="{{ $p->nama }}" loading="lazy">
                @else
                    {{ $p->inisial }}
                @endif
            </span>
            <span class="simpul-jabatan">{{ $p->jabatan }}</span>
            <span class="simpul-nama">{{ $p->nama }}</span>
            @if ($p->keterangan)<span class="simpul-ket">{{ $p->keterangan }}</span>@endif
        </div>

        {{-- Lembaga mitra (mis. BPD) di samping puncak struktur, garis koordinasi putus-putus --}}
        @if ($level === 0 && $mitra->isNotEmpty())
            <div class="mitra-wadah">
                @foreach ($mitra as $m)
                    <div class="simpul simpul-mitra" tabindex="0">
                        <span class="simpul-foto">
                            @if ($m->foto)<img src="{{ $m->foto_url }}" alt="{{ $m->nama }}" loading="lazy">@else{{ $m->inisial }}@endif
                        </span>
                        <span class="simpul-jabatan">{{ $m->jabatan }}</span>
                        <span class="simpul-nama">{{ $m->nama }}</span>
                        @if ($m->keterangan)<span class="simpul-ket">{{ $m->keterangan }}</span>@endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ($p->bawahan->isNotEmpty())
        <ul>
            @foreach ($p->bawahan as $b)
                @include('struktur._simpul', ['p' => $b, 'level' => $level + 1, 'mitra' => $mitra])
            @endforeach
        </ul>
    @endif
</li>
