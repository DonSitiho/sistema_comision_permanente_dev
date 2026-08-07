<div class="card card-flush shadow-sm p-6 h-100">
    @if (!$comunicado)
        <div class="d-flex align-items-center justify-content-center h-100 text-muted">Selecciona un comunicado enviado para ver su seguimiento.</div>
    @else
        <div class="d-flex align-items-start justify-content-between mb-4">
            <div>
                <h3 class="fw-bold mb-0">{{ $comunicado->titulo }}</h3>
                <span class="text-muted fs-8">Enviado: {{ $comunicado->enviado_at?->format('d M, H:i A') }}</span>
            </div>
            <button type="button" wire:click="cerrar" class="btn btn-icon btn-sm btn-light">&times;</button>
        </div>
        <ul class="nav nav-tabs nav-line-tabs mb-4">
            <li class="nav-item"><a class="nav-link {{ $pestana === 'leidos' ? 'active' : '' }}" href="#" wire:click.prevent="cambiarPestana('leidos')">Leido por ({{ $leidos->count() }})</a></li>
            <li class="nav-item"><a class="nav-link {{ $pestana === 'pendientes' ? 'active' : '' }}" href="#" wire:click.prevent="cambiarPestana('pendientes')">No Abierto ({{ $pendientes->count() }})</a></li>
        </ul>
        <div class="d-flex flex-column gap-3 overflow-auto" style="max-height:50vh;">
            @php($lista = $pestana === 'leidos' ? $leidos : $pendientes)
            @forelse ($lista as $destinatario)
                <div class="d-flex align-items-center justify-content-between" wire:key="dest-{{ $destinatario->id }}">
                    <div>
                        <span class="fw-bold d-block fs-7">{{ $destinatario->name }}</span>
                        <span class="text-muted fs-8">{{ $destinatario->email }}</span>
                    </div>
                    @if ($pestana === 'leidos')
                        <span class="fs-8 text-primary">{{ optional($destinatario->pivot->leido_at)->format('H:i A') }}</span>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-6">Sin registros en esta pestaña.</div>
            @endforelse
        </div>
        @if ($pendientes->count() > 0)
            <div class="pt-4 mt-auto border-top">
                <a href="#" wire:click.prevent="enviarRecordatorio" class="fw-semibold">Enviar Recordatorio a Pendientes</a>
            </div>
        @endif
    @endif
</div>