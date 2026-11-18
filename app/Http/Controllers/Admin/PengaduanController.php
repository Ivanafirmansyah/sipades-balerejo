<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengaduan::with('user');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $query->where('isi', 'like', '%' . $request->q . '%')
                ->orWhere('nama_pelapor', 'like', '%' . $request->q . '%');
        }

        $pengaduans = $query->latest()->paginate(15)->withQueryString();
        $stats = [
            'baru' => Pengaduan::where('status', 'baru')->count(),
            'diproses' => Pengaduan::where('status', 'diproses')->count(),
            'selesai' => Pengaduan::where('status', 'selesai')->count(),
        ];

        return view('admin.pengaduan.index', compact('pengaduans', 'stats'));
    }

    public function show(Pengaduan $pengaduan)
    {
        $pengaduan->load('user');

        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    public function update(Request $request, Pengaduan $pengaduan)
    {
        $data = $request->validate([
            'status' => ['required', 'in:baru,diproses,selesai'],
            'tanggapan' => ['nullable', 'string'],
        ]);

        $data['tanggapan_at'] = $data['tanggapan'] ? now() : $pengaduan->tanggapan_at;
        $pengaduan->update($data);

        return back()->with('success', 'Pengaduan berhasil diperbarui.');
    }

    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();

        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }
}
