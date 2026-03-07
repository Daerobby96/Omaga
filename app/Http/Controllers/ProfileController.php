<?php

// ============================================================
// app/Http/Controllers/ProfileController.php
// ============================================================
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        
        return match($user->getRoleNames()->first()) {
            'mahasiswa' => view('profile.mahasiswa', ['user' => $user]),
            'dosen' => view('profile.dosen', ['user' => $user]),
            'mitra' => view('profile.mitra', ['user' => $user]),
            'admin' => view('profile.admin', ['user' => $user]),
            default => abort(403),
        };
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            // Mahasiswa fields
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:500',
            // Mitra fields
            'telepon' => 'nullable|string|max:20',
            'tanda_tangan' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Update user basic info
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        // Update role-specific data
        match($role) {
            'mahasiswa' => $this->updateMahasiswa($user, $request),
            'dosen' => $this->updateDosen($user, $request),
            'mitra' => $this->updateMitra($user, $request),
            default => null,
        };

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function updateMahasiswa($user, Request $request)
    {
        if ($user->mahasiswa) {
            $user->mahasiswa->no_hp = $request->no_hp;
            $user->mahasiswa->alamat = $request->alamat;
            
            // Handle CV upload
            if ($request->hasFile('cv')) {
                $user->mahasiswa->cv = $request->file('cv')->store('mahasiswa/cv', 'public');
            }
            
            // Handle Transkrip upload
            if ($request->hasFile('transkrip')) {
                $user->mahasiswa->transkrip = $request->file('transkrip')->store('mahasiswa/transkrip', 'public');
            }
            
            $user->mahasiswa->save();
        }
    }

    private function updateDosen($user, Request $request)
    {
        if ($user->dosen) {
            $user->dosen->no_hp = $request->no_hp;
            $user->dosen->alamat = $request->alamat;
            $user->dosen->save();
        }
    }

    private function updateMitra($user, Request $request)
    {
        if ($user->mitra) {
            $user->mitra->telepon = $request->telepon;
            $user->mitra->alamat = $request->alamat;
            
            // Handle tanda tangan upload
            if ($request->hasFile('tanda_tangan')) {
                // Hapus file lama jika ada
                if ($user->mitra->tanda_tangan && \Storage::disk('public')->exists($user->mitra->tanda_tangan)) {
                    \Storage::disk('public')->delete($user->mitra->tanda_tangan);
                }
                $user->mitra->tanda_tangan = $request->file('tanda_tangan')->store('mitra/ttd', 'public');
            }
            
            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Hapus file lama jika ada
                if ($user->mitra->logo && \Storage::disk('public')->exists($user->mitra->logo)) {
                    \Storage::disk('public')->delete($user->mitra->logo);
                }
                $user->mitra->logo = $request->file('logo')->store('mitra/logo', 'public');
            }
            
            $user->mitra->save();
        }
    }
}
