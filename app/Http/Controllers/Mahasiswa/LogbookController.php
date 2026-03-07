<?php

// ============================================================
// app/Http/Controllers/Mahasiswa/LogbookController.php
// ============================================================
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanMagang;
use App\Models\Logbook;
use App\Http\Requests\Requests\StoreLogbookRequest;

class LogbookController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuanAktif = PengajuanMagang::where('mahasiswa_id',$mahasiswa->id)
            ->whereIn('status',['berjalan','diterima_mitra'])->first();

        abort_unless($pengajuanAktif, 403, 'Tidak ada magang aktif.');

        $logbook = Logbook::where('pengajuan_id',$pengajuanAktif->id)
            ->orderByDesc('tanggal')->paginate(20);

        return view('mahasiswa.logbook.index', compact('logbook','pengajuanAktif'));
    }

    public function create()
    {
        $mahasiswa      = Auth::user()->mahasiswa;
        $pengajuanAktif = PengajuanMagang::where('mahasiswa_id',$mahasiswa->id)
            ->whereIn('status',['berjalan','diterima_mitra'])->first();
        abort_unless($pengajuanAktif, 403, 'Tidak ada magang aktif.');

        // Cek sudah mengisi logbook hari ini
        $sudahHariIni = Logbook::where('pengajuan_id',$pengajuanAktif->id)
            ->whereDate('tanggal',today())->exists();
        abort_if($sudahHariIni, 403, 'Logbook hari ini sudah diisi. Harap tunggu besok untuk mengisi logbook lagi.');

        return view('mahasiswa.logbook.create', compact('pengajuanAktif'));
    }

    public function store(StoreLogbookRequest $request)
    {
        $mahasiswa      = Auth::user()->mahasiswa;
        $pengajuanAktif = PengajuanMagang::where('mahasiswa_id',$mahasiswa->id)
            ->whereIn('status',['berjalan','diterima_mitra'])->firstOrFail();

        $data = $request->validated();
        $data['pengajuan_id'] = $pengajuanAktif->id;
        $data['mahasiswa_id'] = $mahasiswa->id;
        $data['status']       = $request->action === 'submit' ? 'submitted' : 'draft';

        if ($request->hasFile('foto_kegiatan'))
            $data['foto_kegiatan'] = $request->file('foto_kegiatan')->store('logbook/foto','public');

        Logbook::create($data);

        return redirect()->route('mahasiswa.logbook.index')
            ->with('success', $data['status'] === 'submitted' ? 'Logbook berhasil dikirim.' : 'Logbook disimpan sebagai draft.');
    }

    public function edit(Logbook $logbook)
    {
        abort_unless($logbook->mahasiswa_id === Auth::user()->mahasiswa->id, 403);
        abort_unless(in_array($logbook->status,['draft','revisi']), 403, 'Logbook tidak dapat diedit.');
        return view('mahasiswa.logbook.edit', compact('logbook'));
    }

    public function update(StoreLogbookRequest $request, Logbook $logbook)
    {
        abort_unless($logbook->mahasiswa_id === Auth::user()->mahasiswa->id, 403);
        $data = $request->validated();
        $data['status'] = $request->action === 'submit' ? 'submitted' : 'draft';
        if ($request->hasFile('foto_kegiatan'))
            $data['foto_kegiatan'] = $request->file('foto_kegiatan')->store('logbook/foto','public');
        $logbook->update($data);
        return redirect()->route('mahasiswa.logbook.index')->with('success','Logbook diperbarui.');
    }

    public function show(Logbook $logbook)
    {
        abort_unless($logbook->mahasiswa_id === Auth::user()->mahasiswa->id, 403);
        return view('mahasiswa.logbook.show', compact('logbook'));
    }
}
