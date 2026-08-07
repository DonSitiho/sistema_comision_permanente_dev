@if ($comunicadoActual)
    <div class="modal fade show d-block" id="modalObligatorio" tabindex="-1" style="background: rgba(0,0,0,.6);" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-light-warning">
                    <div>
                        <span class="badge badge-warning mb-2">Comunicado obligatorio</span>
                        <h3 class="modal-title">{{ $comunicadoActual->titulo }}</h3>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="text-muted fs-8 mb-4">
                        Enviado {{ $comunicadoActual->enviado_at?->diffForHumans() }}
                        @if (count($cola) > 1) &middot; {{ count($cola) }} comunicados pendientes por aceptar @endif
                    </div>
                    <div class="fs-6">{!! $comunicadoActual->cuerpo !!}</div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <span class="text-muted fs-8">Puedes cerrar sesion, pero debes aceptar este comunicado para seguir usando el sistema.</span>
                    <button type="button" wire:click="aceptar" class="btn btn-primary">Acepto</button>
                </div>
            </div>
        </div>
    </div>
@endif