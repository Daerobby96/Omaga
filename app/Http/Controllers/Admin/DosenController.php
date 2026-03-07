<?php

// ============================================================
// app/Http/Controllers/Admin/DosenController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\User;
use App\Http\Requests\Requests\StoreDosenRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DosenController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::with('user')
            ->withCount(['bimbinganAktif as aktif_count'])
            ->when($request->search, fn($q,$s) => $q->where('nama_lengkap','like',"%$s%")->orWhere('nidn','like',"$s"))
            ->paginate(15)->withQueryString();
        return view('admin.dosen.index', compact('dosen'));
    }

    public function create() { return view('admin.dosen.create'); }

    public function store(StoreDosenRequest $request)
    {
        DB::transaction(function() use ($request) {
            $user = User::create([
                'name'=>$request->nama_lengkap,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
                'telepon'=>$request->no_hp,
            ]);
            $user->assignRole('dosen');
            $data = $request->validated();
            $data['user_id'] = $user->id;
            $data['is_ketua_prodi'] = $request->has('is_ketua_prodi');
            unset($data['email'],$data['password']);
            Dosen::create($data);
        });
        return redirect()->route('admin.dosen.index')->with('success','Dosen berhasil ditambahkan.');
    }

    public function show(Dosen $dosen)
    {
        $dosen->load(['bimbingan.mahasiswa','bimbingan.mitra']);
        return view('admin.dosen.show', compact('dosen'));
    }

    public function edit(Dosen $dosen)
    {
        return view('admin.dosen.edit', compact('dosen'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nidn' => 'required|string|unique:dosen,nidn,'.$dosen->id,
            'program_studi' => 'required|string',
            'fakultas' => 'required|string',
            'no_hp' => 'nullable|string',
            'kuota_bimbingan' => 'required|integer|min:0|max:20',
            'jabatan_fungsional' => 'nullable|string',
            'is_ketua_prodi' => 'nullable|boolean',
            'prodi_yang_dikelola' => 'nullable|string',
        ]);
        
        $dosen->update($validated);
        
        return redirect()->route('admin.dosen.index')->with('success','Data dosen diperbarui.');
    }

    public function destroy(Dosen $dosen)
    {
        $dosen->user->delete();
        $dosen->delete();
        return redirect()->route('admin.dosen.index')->with('success','Dosen dihapus.');
    }
}
