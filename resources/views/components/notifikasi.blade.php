<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Notifikasi</h5>
                </div>
                <div class="card-body p-0">
                    @if($notif->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($notif as $item)
                                <div class="list-group-item {{ $item->dibaca ? '' : 'bg-light' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="me-3">
                                            @switch($item->tipe)
                                                @case('success')
                                                    <i class="fas fa-check-circle text-success"></i>
                                                    @break
                                                @case('danger')
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                    @break
                                                @case('warning')
                                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                                    @break
                                                @default
                                                    <i class="fas fa-info-circle text-info"></i>
                                            @endswitch
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 {{ $item->dibaca ? 'text-muted' : 'fw-semibold' }}">
                                                {{ $item->judul }}
                                            </h6>
                                            <p class="mb-1 text-muted small">{{ $item->pesan }}</p>
                                            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($item->url)
                                            <a href="{{ $item->url }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="card-footer">
                            {{ $notif->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash text-muted fa-3x mb-3"></i>
                            <p class="text-muted">Tidak ada notifikasi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
