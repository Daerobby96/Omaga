@extends('layouts.app')

@section('title', 'Forum Diskusi - ' . $pengajuan->kode_pengajuan)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="bi bi-chat-dots"></i> Forum Diskusi
                    </h5>
                    <p class="text-muted mb-0">
                        Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong> | 
                        Mitra: <strong>{{ $pengajuan->mitra->nama_perusahaan }}</strong>
                    </p>
                </div>
            </div>

            <!-- Form Kirim Pesan -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('diskusi.store', $pengajuan) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea 
                                name="isi" 
                                class="form-control @error('isi') is-invalid @enderror" 
                                rows="3" 
                                placeholder="Tulis pesan diskusi..."
                                required
                            ></textarea>
                            @error('isi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Daftar Diskusi -->
            <div class="card">
                <div class="card-header bg-white">
                    <h6 class="mb-0">
                        <i class="bi bi-chat-square-text"></i> Riwayat Diskusi 
                        <span class="badge bg-secondary">{{ $diskusi->total() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($diskusi as $item)
                        <div class="border-bottom mb-3 pb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 36px; height: 36px;">
                                        {{ strtoupper(substr($item->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $item->user->name }}</strong>
                                        <small class="text-muted d-block">
                                            {{ $item->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark">
                                    {{ $item->user->getRoleNames()->first() }}
                                </span>
                            </div>
                            
                            <div class="mt-2 ps-5">
                                <p class="mb-2">{{ $item->isi }}</p>
                                <button class="btn btn-sm btn-link text-decoration-none" 
                                        onclick="document.getElementById('reply-{{ $item->id }}').classList.toggle('d-none')">
                                    <i class="bi bi-reply"></i> Balas
                                </button>
                            </div>

                            <!-- Form Reply -->
                            <div id="reply-{{ $item->id }}" class="d-none ps-5 mt-2">
                                <form action="{{ route('diskusi.store', $pengajuan) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="parent_id" value="{{ $item->id }}">
                                    <div class="input-group">
                                        <input type="text" name="isi" class="form-control form-control-sm" 
                                               placeholder="Tulis balasan..." required>
                                        <button class="btn btn-sm btn-primary" type="submit">Kirim</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Replies -->
                            @if($item->replies->count() > 0)
                                <div class="mt-3 ps-5">
                                    @foreach($item->replies as $reply)
                                        <div class="border-start border-2 border-primary ps-3 mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 28px; height: 28px; font-size: 12px;">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $reply->user->name }}</strong>
                                                    <small class="text-muted">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <p class="mb-0 mt-1">{{ $reply->isi }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-square fs-1"></i>
                            <p class="mb-0">Belum ada diskusi. Mulai percakapan pertama!</p>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $diskusi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
