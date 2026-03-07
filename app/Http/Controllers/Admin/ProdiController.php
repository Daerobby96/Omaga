<?php

// ============================================================
// app/Http/Controllers/Admin/ProdiController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prodi;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $prodi = Prodi::when($request->search, fn($q, $s) => 
            $q->where('nama_prodi', 'like', "%$s%")->orWhere('kode_prodi', 'like', "%$s%")
        )->orderBy('fakultas')->orderBy('nama_prodi')->paginate(15)->withQueryString();
        
        return view('admin.prodi.index', compact('prodi'));
    }

    public function create()
    {
        return view('admin.prodi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodi,nama_prodi',
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi',
            'fakultas' => 'nullable|string',
            'jenjang' => 'required|in:S1,D3,D4',
            'akreditasi' => 'nullable|string|max:2',
            'tahun_akreditasi' => 'nullable|digits:4|integer|min:2000|max:'.date('Y'),
            'status' => 'required|in:aktif,nonaktif',
        ]);

        Prodi::create($validated);
        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil ditambahkan.');
    }

    public function show(Prodi $prodi)
    {
        $prodi->loadCount(['mahasiswa', 'dosen', 'pengajuan']);
        return view('admin.prodi.show', compact('prodi'));
    }

    public function edit(Prodi $prodi)
    {
        return view('admin.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255|unique:prodi,nama_prodi,'.$prodi->id,
            'kode_prodi' => 'required|string|max:10|unique:prodi,kode_prodi,'.$prodi->id,
            'fakultas' => 'nullable|string',
            'jenjang' => 'required|in:S1,D3,D4',
            'akreditasi' => 'nullable|string|max:2',
            'tahun_akreditasi' => 'nullable|digits:4|integer|min:2000|max:'.date('Y'),
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $prodi->update($validated);
        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi berhasil diperbarui.');
    }

    public function destroy(Prodi $prodi)
    {
        $prodi->delete();
        return redirect()->route('admin.prodi.index')->with('success', 'Program Studi dihapus.');
    }
}
