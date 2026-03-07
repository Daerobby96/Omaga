{{-- resources/views/ketua_prodi/prodi/index.blade.php --}}
@extends('layouts.app')
@section('title','Data Prodi')
@section('page-title','Data Prodi')
@section('page-sub', $prodi)

@section('content')
<div class="row">
<div class="col-xl-8">

<div class="card">
    <div class="card-header">
        <span class="card-header-title">Program Studi {{ $prodi }}</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Prodi</th>
                        <th>Fakultas</th>
                        <th>Jenjang</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prodis as $p)
                    <tr>
                        <td>{{ $p->kode_prodi }}</td>
                        <td class="fw-semibold">{{ $p->nama_prodi }}</td>
                        <td>{{ $p->fakultas ?? '-' }}</td>
                        <td>{{ $p->jenjang }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Data program studi tidak ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</div>
@endsection
