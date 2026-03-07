<?php
// ============================================================
// app/Http/Controllers/Admin/MahasiswaController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Requests\StoreMahasiswaRequest;
use App\Models\{Mahasiswa, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash, Storage};

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with('user')
            ->when($request->search, fn($q,$s) =>
                $q->where('nim','like',"%$s%")->orWhere('nama_lengkap','like',"%$s%")
            )
            ->when($request->prodi,    fn($q,$v) => $q->where('program_studi',$v))
            ->when($request->angkatan, fn($q,$v) => $q->where('angkatan',$v))
            ->when($request->status,   fn($q,$v) => $q->where('status_akademik',$v));

        $mahasiswa = $query->orderBy('nim')->paginate(15)->withQueryString();
        $prodi     = Mahasiswa::distinct()->pluck('program_studi');
        $angkatan  = Mahasiswa::distinct()->pluck('angkatan')->sortDesc();

        return view('admin.mahasiswa.index', compact('mahasiswa','prodi','angkatan'));
    }

    public function create()
    {
        return view('admin.mahasiswa.create');
    }

    public function store(StoreMahasiswaRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Buat User
            $user = User::create([
                'name'     => $request->nama_lengkap,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'telepon'  => $request->no_hp,
            ]);
            $user->assignRole('mahasiswa');

            // Upload file
            $data            = $request->validated();
            $data['user_id'] = $user->id;

            foreach (['foto','cv','transkrip'] as $field) {
                if ($request->hasFile($field)) {
                    $data[$field] = $request->file($field)->store("mahasiswa/$field",'public');
                }
            }
            unset($data['email'],$data['password']);

            Mahasiswa::create($data);
        });

        return redirect()->route('admin.mahasiswa.index')
            ->with('success','Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['user','pengajuan.mitra','pengajuan.dosen','penilaian','sertifikat']);
        return view('admin.mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        return view('admin.mahasiswa.edit', compact('mahasiswa'));
    }

    public function update(StoreMahasiswaRequest $request, Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($request, $mahasiswa) {
            // Update user
            $userUpdate = ['name'=>$request->nama_lengkap, 'email'=>$request->email];
            if ($request->password) $userUpdate['password'] = Hash::make($request->password);
            $mahasiswa->user->update($userUpdate);

            // Update files
            $data = $request->validated();
            foreach (['foto','cv','transkrip'] as $field) {
                if ($request->hasFile($field)) {
                    if ($mahasiswa->$field) Storage::disk('public')->delete($mahasiswa->$field);
                    $data[$field] = $request->file($field)->store("mahasiswa/$field",'public');
                }
            }
            unset($data['email'],$data['password'],$data['nim']);

            $mahasiswa->update($data);
        });

        return redirect()->route('admin.mahasiswa.show',$mahasiswa)
            ->with('success','Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        DB::transaction(function () use ($mahasiswa) {
            foreach (['foto','cv','transkrip'] as $field) {
                if ($mahasiswa->$field) Storage::disk('public')->delete($mahasiswa->$field);
            }
            $mahasiswa->user->delete(); // cascade deletes mahasiswa
        });

        return redirect()->route('admin.mahasiswa.index')
            ->with('success','Data mahasiswa berhasil dihapus.');
    }
}