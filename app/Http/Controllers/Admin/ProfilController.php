<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageCompressor;
use App\Models\ProfilDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $profil = ProfilDesa::first() ?? new ProfilDesa();

        return view('admin.profil.edit', compact('profil'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_desa' => ['required', 'string', 'max:100'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'sejarah' => ['nullable', 'string'],
            'visi' => ['nullable', 'string'],
            'misi' => ['nullable', 'string'],
            'deskripsi' => ['nullable', 'string'],
            'alamat_kantor' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'peta_embed' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:10240'],
        ]);

        $profil = ProfilDesa::first();

        if ($request->hasFile('logo')) {
            if ($profil?->logo) {
                Storage::disk('public')->delete($profil->logo);
            }
            $data['logo'] = app(ImageCompressor::class)->store($request->file('logo'), 'profil', 800);
        }

        if ($profil) {
            $profil->update($data);
        } else {
            ProfilDesa::create($data);
        }

        return back()->with('success', 'Profil desa berhasil diperbarui.');
    }
}
