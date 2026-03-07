<?php

// ============================================================
// app/Http/Controllers/Auth/RegisterMahasiswaController.php
// Mahasiswa bisa self-register, status pending menunggu admin/koordinator
// ============================================================
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterMahasiswaController extends Controller
{
    public function showForm()
    {
        return view('auth.register-mahasiswa');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nim'               => 'required|string|unique:mahasiswa,nim',
            'nama_lengkap'      => 'required|string|max:200',
            'program_studi'    => 'required|string|max:100',
            'fakultas'          => 'required|string|max:100',
            'semester'          => 'required|integer|min:1|max:14',
            'angkatan'          => 'required|integer|min:2020|max:2030',
            'ipk'               => 'nullable|numeric|min:0|max:4',
            'email'             => 'required|email|unique:users,email',
            'password'          => 'required|min:8|confirmed',
            'no_hp'             => 'nullable|string|max:20',
            'alamat'            => 'nullable|string',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'telepon'  => $request->no_hp,
                'is_active'=> false, // menunggu verifikasi admin
            ]);
            $user->assignRole('mahasiswa');

            Mahasiswa::create([
                'user_id'          => $user->id,
                'nim'              => $request->nim,
                'nama_lengkap'     => $request->nama_lengkap,
                'program_studi'    => $request->program_studi,
                'fakultas'         => $request->fakultas,
                'semester'         => $request->semester,
                'angkatan'         => $request->angkatan,
                'ipk'              => $request->ipk ?? 0,
                'no_hp'            => $request->no_hp,
                'alamat'           => $request->alamat,
                'status_akademik' => 'aktif',
            ]);
        });

        return redirect()->route('login')
            ->with('success','Pendaftaran berhasil! Akun Anda menunggu verifikasi dari Koordinator Magang.');
    }
}
