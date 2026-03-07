<?php

// ============================================================
// app/Http/Controllers/Admin/MitraController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mitra;
use App\Models\User;
use App\Http\Requests\Requests\StoreMitraRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $mitra = Mitra::with('user')
            ->when($request->search, fn($q,$s) => $q->where('nama_perusahaan','like',"%$s%"))
            ->when($request->status, fn($q,$v) => $q->where('status',$v))
            ->orderBy('nama_perusahaan')
            ->paginate(15)->withQueryString();

        return view('admin.mitra.index', compact('mitra'));
    }

    public function create() { return view('admin.mitra.create'); }

    public function store(StoreMitraRequest $request)
    {
        DB::transaction(function() use ($request) {
            $user = User::create([
                'name'    => $request->nama_kontak,
                'email'   => $request->email,
                'password'=> Hash::make($request->password),
            ]);
            $user->assignRole('mitra');
            $data = $request->validated();
            $data['user_id'] = $user->id;
            if ($request->hasFile('logo')) $data['logo'] = $request->file('logo')->store('mitra/logo','public');
            unset($data['email'],$data['password']);
            Mitra::create($data);
        });
        return redirect()->route('admin.mitra.index')->with('success','Mitra berhasil ditambahkan.');
    }

    public function show(Mitra $mitra)
    {
        $mitra->load(['pengajuan.mahasiswa','user']);
        return view('admin.mitra.show', compact('mitra'));
    }

    public function edit(Mitra $mitra) { return view('admin.mitra.edit', compact('mitra')); }

    public function update(StoreMitraRequest $request, Mitra $mitra)
    {
        DB::transaction(function() use ($request, $mitra) {
            $upd = ['name'=>$request->nama_kontak,'email'=>$request->email];
            if ($request->password) $upd['password'] = Hash::make($request->password);
            $mitra->user->update($upd);
            $data = $request->validated();
            if ($request->hasFile('logo')) {
                if ($mitra->logo) Storage::disk('public')->delete($mitra->logo);
                $data['logo'] = $request->file('logo')->store('mitra/logo','public');
            }
            unset($data['email'],$data['password']);
            $mitra->update($data);
        });
        return redirect()->route('admin.mitra.show',$mitra)->with('success','Data mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra)
    {
        $mitra->user->delete();
        return redirect()->route('admin.mitra.index')->with('success','Mitra berhasil dihapus.');
    }

    public function aktivasi(Mitra $mitra)
    {
        $status = $mitra->status === 'aktif' ? 'nonaktif' : 'aktif';
        $mitra->update(['status'=>$status]);
        return back()->with('success',"Status mitra diubah menjadi $status.");
    }
}
