<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Mesin skema untuk Dynamic Form Builder.
 *
 * Skema disimpan sebagai JSON: {"fields": [ {type, name, label, required, placeholder, help, options, min, max, width}, ... ]}
 * Kelas ini membersihkan skema dari builder, membuat aturan validasi, dan memformat jawaban untuk ditampilkan.
 */
class FormSchema
{
    public const TYPES = [
        'text' => 'Teks Singkat',
        'textarea' => 'Teks Panjang',
        'number' => 'Angka',
        'nik' => 'NIK (16 digit)',
        'email' => 'Email',
        'tel' => 'No. Telepon',
        'date' => 'Tanggal',
        'select' => 'Pilihan (Dropdown)',
        'radio' => 'Pilihan Ganda',
        'checkbox' => 'Kotak Centang (boleh lebih dari satu)',
        'section' => 'Judul Bagian',
    ];

    public const MAX_FIELDS = 60;

    /** Bersihkan skema mentah dari builder: buang yang tidak valid, buat nama unik. */
    public static function normalize(mixed $raw): array
    {
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }
        $fields = [];
        $used = [];

        foreach (array_slice((array) ($raw['fields'] ?? []), 0, self::MAX_FIELDS) as $f) {
            if (! is_array($f)) {
                continue;
            }
            $type = $f['type'] ?? '';
            $label = trim(strip_tags((string) ($f['label'] ?? '')));
            if (! array_key_exists($type, self::TYPES) || $label === '') {
                continue;
            }

            $base = Str::slug($f['name'] ?? '' ?: $label, '_') ?: 'field';
            $base = Str::limit($base, 40, '');
            $name = $base;
            for ($i = 2; in_array($name, $used, true); $i++) {
                $name = $base . '_' . $i;
            }
            $used[] = $name;

            $field = [
                'type' => $type,
                'name' => $name,
                'label' => Str::limit($label, 200, ''),
            ];

            if ($type !== 'section') {
                $field['required'] = (bool) ($f['required'] ?? false);
                $field['placeholder'] = Str::limit(trim((string) ($f['placeholder'] ?? '')), 150, '');
                $field['width'] = ($f['width'] ?? 'full') === 'half' ? 'half' : 'full';
            }
            $field['help'] = Str::limit(trim(strip_tags((string) ($f['help'] ?? ''))), 300, '');

            if (in_array($type, ['select', 'radio', 'checkbox'], true)) {
                $opts = is_array($f['options'] ?? null) ? $f['options'] : preg_split('/\r?\n/', (string) ($f['options'] ?? ''));
                $opts = array_values(array_unique(array_filter(array_map(fn ($o) => Str::limit(trim(strip_tags((string) $o)), 120, ''), $opts), 'strlen')));
                if (! $opts) {
                    continue; // pilihan tanpa opsi tidak bisa diisi
                }
                $field['options'] = array_slice($opts, 0, 50);
            }

            if ($type === 'number') {
                $field['min'] = is_numeric($f['min'] ?? null) ? (float) $f['min'] : null;
                $field['max'] = is_numeric($f['max'] ?? null) ? (float) $f['max'] : null;
            }

            $fields[] = $field;
        }

        return ['fields' => $fields];
    }

    /** Aturan validasi Laravel untuk isian berdasarkan skema (kunci: data.<nama>). */
    public static function rules(array $fields): array
    {
        $rules = [];
        foreach ($fields as $f) {
            $key = 'data.' . $f['name'];
            $base = ! empty($f['required']) ? ['required'] : ['nullable'];

            $rules[$key] = match ($f['type']) {
                'text' => [...$base, 'string', 'max:255'],
                'textarea' => [...$base, 'string', 'max:5000'],
                'number' => array_filter([...$base, 'numeric',
                    isset($f['min']) ? 'min:' . $f['min'] : null,
                    isset($f['max']) ? 'max:' . $f['max'] : null]),
                'nik' => [...$base, 'digits:16'],
                'email' => [...$base, 'email', 'max:150'],
                'tel' => [...$base, 'regex:/^[0-9+\-\s]{6,20}$/'],
                'date' => [...$base, 'date'],
                'select', 'radio' => [...$base, 'string', Rule::in($f['options'])],
                'checkbox' => [...$base, 'array'],
                default => ['nullable'],
            };

            if ($f['type'] === 'checkbox') {
                $rules[$key . '.*'] = ['string', Rule::in($f['options'])];
            }
        }

        return $rules;
    }

    /** Nama tampilan field untuk pesan error. */
    public static function attributes(array $fields): array
    {
        $attr = [];
        foreach ($fields as $f) {
            $attr['data.' . $f['name']] = $f['label'];
            $attr['data.' . $f['name'] . '.*'] = $f['label'];
        }

        return $attr;
    }

    /** Pesan error berbahasa Indonesia. */
    public static function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'digits' => ':attribute harus :digits digit angka.',
            'email' => ':attribute harus berupa alamat email yang benar.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ['numeric' => ':attribute minimal :min.', 'string' => ':attribute minimal :min karakter.', 'array' => 'Pilih minimal :min.'],
            'max' => ['numeric' => ':attribute maksimal :max.', 'string' => ':attribute maksimal :max karakter.', 'array' => 'Pilih maksimal :max.'],
            'date' => ':attribute harus berupa tanggal.',
            'in' => 'Pilihan :attribute tidak valid.',
            'regex' => 'Format :attribute tidak valid.',
            'array' => 'Pilihan :attribute tidak valid.',
        ];
    }

    /** Ambil hanya nilai yang ada di skema (abaikan input lain). */
    public static function pick(array $fields, array $input): array
    {
        $out = [];
        foreach ($fields as $f) {
            $v = $input[$f['name']] ?? null;
            if ($f['type'] === 'checkbox') {
                $v = array_values((array) ($v ?? []));
            } elseif (is_string($v)) {
                $v = trim($v);
            }
            $out[$f['name']] = $v === '' ? null : $v;
        }

        return $out;
    }

    /** Format nilai untuk ditampilkan ke admin. */
    public static function display(array $field, mixed $value): string
    {
        if ($value === null || $value === '' || $value === []) {
            return '-';
        }
        if (is_array($value)) {
            return implode(', ', $value);
        }
        if ($field['type'] === 'date') {
            try {
                return \Illuminate\Support\Carbon::parse($value)->translatedFormat('d F Y');
            } catch (\Throwable) {
                return (string) $value;
            }
        }

        return (string) $value;
    }
}
