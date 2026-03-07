<?php

// ============================================================
// app/Http/Controllers/Auth/RegisterMitraController.php
// Mitra bisa self-register, status pending menunggu admin
// ============================================================
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mitra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterMitraController extends Controller
{
    public function showForm()
    {
        return view('auth.register-mitra');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_perusahaan' => 'required|string|max:200',
            'bidang_usaha'    => 'required|string|max:100',
            'nama_kontak'     => 'required|string|max:100',
            'jabatan_kontak'  => 'nullable|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|min:8|confirmed',
            'email_perusahaan'=> 'required|email',
            'telepon'         => 'nullable|string|max:20',
            'alamat'          => 'required|string',
            'website'         => 'nullable|url',
            'deskripsi'       => 'nullable|string|max:1000',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name'     => $request->nama_kontak,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'telepon'  => $request->telepon,
            ]);
            $user->assignRole('mitra');

            Mitra::create([
                'user_id'          => $user->id,
                'nama_perusahaan'  => $request->nama_perusahaan,
                'bidang_usaha'     => $request->bidang_usaha,
                'nama_kontak'      => $request->nama_kontak,
                'jabatan_kontak'   => $request->jabatan_kontak,
                'email_perusahaan' => $request->email_perusahaan,
                'telepon'          => $request->telepon,
                'alamat'           => $request->alamat,
                'website'          => $request->website,
                'deskripsi'        => $request->deskripsi,
                'status'           => 'pending', // menunggu verifikasi admin
            ]);
        });

        return redirect()->route('login')
            ->with('success','Pendaftaran berhasil! Akun Anda sedang diverifikasi administrator.');
    }
}
