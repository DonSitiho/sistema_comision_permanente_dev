{{-- resources/views/livewire/notificaciones/centro-notificaciones.blade.php --}} 
<div class="dropdown" wire:ignore.self>
    <button class="btn btn-icon position-relative" data-bs-toggle="dropdown">
        {!! getIcon("notification", "fs-2") !!}
        @if ($contador > 0)
            <span class="badge badge-circle badge-danger position-absolute top-0 end-0">
            {{ $contador }}
            </span>
        @endif
    </button>
    <div class="dropdown-menu dropdown-menu-end p-0" style="width:340px">
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <span class="fw-bold">Notificaciones</span>
            <button wire:click="marcarTodasLeidas" class="btn btn-sm btn-link p-0">
                Marcar todas leidas
            </button>
        </div>
        <div style="max-height:360px; overflow-y:auto">
            @forelse ($notificaciones as $n)
                <a href="{{ $n->url_destino ?? "#" }}" wire:click="marcarLeida({{ $n->id }})"
                    class="d-block p-3 border-bottom text-decoration-none
                    {{ $n->leida_at ? "" : "bg-light-primary" }}">
                    <div class="fw-semibold">{{ $n->titulo }}</div>
                    <div class="text-muted small">{{ $n->mensaje }}</div>
                </a>
            @empty
                <div class="text-center text-muted p-5">Sin notificaciones.</div>
            @endforelse
        </div>
    </div>
</div>
