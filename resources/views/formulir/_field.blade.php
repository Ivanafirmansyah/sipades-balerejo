{{-- Satu isian formulir dinamis, dirender dari skema JSON --}}
@php
    $nm = 'data[' . $f['name'] . ']';
    $id = 'f_' . $f['name'];
    $req = !empty($f['required']);
    $old = old('data.' . $f['name']);
@endphp

@if ($f['type'] === 'section')
    <div class="col-12">
        <div class="form-section-title">{{ $f['label'] }}</div>
        @if (!empty($f['help']))<div class="form-text mt-0">{{ $f['help'] }}</div>@endif
    </div>
@else
    <div class="{{ ($f['width'] ?? 'full') === 'half' ? 'col-md-6' : 'col-12' }}" data-field="{{ $f['name'] }}">
        <label class="form-label" @if (!in_array($f['type'], ['radio', 'checkbox'])) for="{{ $id }}" @endif>
            {{ $f['label'] }} @if ($req)<span class="text-danger">*</span>@endif
        </label>

        @switch($f['type'])
            @case('textarea')
                <textarea id="{{ $id }}" name="{{ $nm }}" rows="4" class="form-control" placeholder="{{ $f['placeholder'] ?? '' }}" maxlength="5000" @required($req)>{{ $old }}</textarea>
                @break
            @case('select')
                <select id="{{ $id }}" name="{{ $nm }}" class="form-select" @required($req)>
                    <option value="">{{ ($f['placeholder'] ?? '') ?: 'Pilih...' }}</option>
                    @foreach ($f['options'] as $o)<option value="{{ $o }}" @selected($old === $o)>{{ $o }}</option>@endforeach
                </select>
                @break
            @case('radio')
                <div class="pilihan-group">
                    @foreach ($f['options'] as $i => $o)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="{{ $nm }}" id="{{ $id . '_' . $i }}" value="{{ $o }}" @checked($old === $o) @required($req)>
                            <label class="form-check-label" for="{{ $id . '_' . $i }}">{{ $o }}</label>
                        </div>
                    @endforeach
                </div>
                @break
            @case('checkbox')
                <div class="pilihan-group" @if ($req) data-required-group @endif>
                    @foreach ($f['options'] as $i => $o)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="{{ $nm }}[]" id="{{ $id . '_' . $i }}" value="{{ $o }}" @checked(in_array($o, (array) $old, true))>
                            <label class="form-check-label" for="{{ $id . '_' . $i }}">{{ $o }}</label>
                        </div>
                    @endforeach
                </div>
                @break
            @case('nik')
                <input id="{{ $id }}" name="{{ $nm }}" type="text" inputmode="numeric" pattern="[0-9]{16}" maxlength="16" class="form-control" placeholder="{{ ($f['placeholder'] ?? '') ?: '16 digit NIK' }}" value="{{ $old }}" title="NIK harus 16 digit angka" @required($req)>
                @break
            @case('number')
                <input id="{{ $id }}" name="{{ $nm }}" type="number" step="any" class="form-control" placeholder="{{ $f['placeholder'] ?? '' }}" value="{{ $old }}"
                       @isset($f['min']) min="{{ $f['min'] }}" @endisset @isset($f['max']) max="{{ $f['max'] }}" @endisset @required($req)>
                @break
            @case('date')
                <input id="{{ $id }}" name="{{ $nm }}" type="date" class="form-control" value="{{ $old }}" @required($req)>
                @break
            @case('email')
                <input id="{{ $id }}" name="{{ $nm }}" type="email" class="form-control" placeholder="{{ $f['placeholder'] ?? '' }}" value="{{ $old }}" maxlength="150" @required($req)>
                @break
            @case('tel')
                <input id="{{ $id }}" name="{{ $nm }}" type="tel" inputmode="tel" pattern="[0-9+\-\s]{6,20}" class="form-control" placeholder="{{ ($f['placeholder'] ?? '') ?: '08xxxxxxxxxx' }}" value="{{ $old }}" @required($req)>
                @break
            @default
                <input id="{{ $id }}" name="{{ $nm }}" type="text" class="form-control" placeholder="{{ $f['placeholder'] ?? '' }}" value="{{ $old }}" maxlength="255" @required($req)>
        @endswitch

        @if (!empty($f['help']))<div class="form-text">{{ $f['help'] }}</div>@endif
        <div class="invalid-feedback d-block field-error">@error('data.' . $f['name']){{ $message }}@enderror</div>
    </div>
@endif
