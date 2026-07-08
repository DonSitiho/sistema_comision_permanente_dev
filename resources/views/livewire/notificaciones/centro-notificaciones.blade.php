{{-- resources/views/livewire/notificaciones/centro-notificaciones.blade.php --}}
<div wire:ignore.self>
    <!-- Botón de la campana -->
    <div class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px position-relative" 
         data-kt-menu-trigger="click" 
         data-kt-menu-attach="parent" 
         data-kt-menu-placement="bottom-end">
         
        {!! getIcon('notification', 'fs-2') !!}
        
        @if ($contador > 0)
            <span class="badge badge-circle badge-danger position-absolute top-0 end-0 translate-middle-y mt-1 me-1 w-15px h-15px fs-9">
                {{ $contador }}
            </span>
        @endif
    </div>

    <!-- Dropdown -->
    <div class="menu menu-sub menu-sub-dropdown menu-column w-350px p-0 bg-white" data-kt-menu="true" style="z-index: 1050;">
        <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
            <span class="fw-bold text-gray-800">Notificaciones</span>
            @if ($contador > 0)
                <button wire:click="marcarTodasLeidas" class="btn btn-sm btn-link p-0 text-primary fw-semibold">
                    Marcar todas como leídas
                </button>
            @endif
        </div>
        
        <div class="scroll-y mh-350px">
            @forelse ($notificaciones as $n)
                <div class="menu-item px-3" wire:key="notif-dropdown-{{ $n->id }}">
                    <a href="#" 
                       wire:click.prevent="marcarLeida({{ $n->id }})"
                       data-kt-menu-dismiss="false"
                       class="menu-link d-flex flex-column align-items-start p-3 border-bottom transition-3s {{ $n->leida_at ? '' : 'bg-light-primary' }}">
                        <div class="fw-bold fs-6 text-gray-800">{{ $n->titulo }}</div>
                        <div class="text-muted fs-7 mt-1 text-truncate w-100">{{ $n->mensaje }}</div>
                    </a>
                </div>
            @empty
                <div class="d-flex flex-column flex-center p-10 text-muted">
                    <span class="fs-6">Sin notificaciones nuevas.</span>
                </div>
            @endforelse
            
            <div class="p-2 border-top text-center">
                <button wire:click="verTodas" class="btn btn-sm btn-active-light-primary text-primary fw-bold w-100 py-2">
                    Ver todas las notificaciones
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TELEPORT: Estructura fija asegurando los botones -->
    @teleport('body')
        <div class="modal fade" id="modalVerNotificacion" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" wire:ignore.self style="z-index: 99999;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold fs-4">
                            {{ $notificacionSeleccionada ? $notificacionSeleccionada->titulo : 'Detalle de Notificación' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body fs-5 text-gray-800">
                        @if($notificacionSeleccionada)
                            {{ $notificacionSeleccionada->mensaje }}
                        @else
                            <div class="text-center p-5">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                        @if($notificacionSeleccionada && $notificacionSeleccionada->url_destino)
                            <a href="{{ $notificacionSeleccionada->url_destino }}" class="btn btn-primary">Ir al sitio</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endteleport

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('mostrar-modal-notif', () => {
                const el = document.getElementById('modalVerNotificacion');
                if (el) {
                    bootstrap.Modal.getOrCreateInstance(el).show();
                }
            });
        });
    </script>
</div>