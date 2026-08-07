<div class="modal fade" id="modalComunicadoVerMas" tabindex="-1" wire:ignore.self
     data-bs-backdrop="{{ $comunicado?->obligatorio ? 'static' : true }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            @if ($comunicado)
                <div class="modal-header">
                    <div>
                        <span class="badge badge-light-{{ $comunicado->categoria->color ?? 'secondary' }} mb-2">{{ $comunicado->categoria->nombre }}</span>
                        <h3 class="modal-title">{{ $comunicado->titulo }}</h3>
                    </div>
                    @unless ($comunicado->obligatorio)
                        <button type="button" class="btn btn-icon btn-sm btn-light" data-bs-dismiss="modal">&times;</button>
                    @endunless
                </div>
                <div class="modal-body">
                    <div class="text-muted fs-8 mb-4">Enviado {{ $comunicado->enviado_at?->diffForHumans() }} por {{ $comunicado->emisor->name }}</div>
                    <div class="fs-6">{!! $comunicado->cuerpo !!}</div>
                </div>
                <div class="modal-footer">
                    @if ($comunicado->obligatorio)
                        <button type="button" wire:click="aceptar" class="btn btn-primary">Acepto</button>
                    @else
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('abrir-modal-ver-mas', () => {
            bootstrap.Modal.getOrCreateInstance(document.getElementById('modalComunicadoVerMas')).show();
        });
        Livewire.on('cerrar-modal-ver-mas', () => {
            bootstrap.Modal.getInstance(document.getElementById('modalComunicadoVerMas'))?.hide();
        });
    });
</script>
@endpush