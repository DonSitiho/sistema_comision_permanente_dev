<div>
    <!-- Buscador por folio, título y fecha -->
    <div class="d-flex justify-content-end w-100 mb-5">
        <div class="position-relative w-25">
            <!-- Icono de lupa estilizado -->
            <span class="position-absolute top-50 translate-middle-y ms-4">
                <i class="ki-duotone ki-magnifier fs-2 text-gray-500">
                    <span class="path1"></span><span class="path2"></span>
                </i>
            </span>

            <input type="text" 
                wire:model.live.debounce.300ms="buscar"
                class="form-control form-control-solid ps-12 pe-5"
                placeholder="Buscar convocatoria"
                autocomplete="off" />
        </div>
    </div>
    
    <!-- Listado de convocatorias -->
    <div class="card card-flush shadow-sm p-6">
        <div class="card-header pt-2 ps-0">
            <h3 class="card-title fw-bold text-gray-800 fs-2">Listado de Convocatorias</h3>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                <thead>
                    <tr class="fw-bold text-muted fs-6">
                        <th class="min-w-100px Folio">Folio</th>
                        <th class="min-w-150px">Título</th>
                        <th class="min-w-150px">Descripción</th>
                        <th class="min-w-150px">Fecha Sesión</th>
                        <th class="min-w-100px">Lugar</th>
                        <th class="min-w-100px">Estado</th>
                        <th class="min-w-100px text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($convocatorias as $convocatoria)
                        <tr>
                            <td><span class="text-gray-800 fw-bold fs-6">{{ $convocatoria->folio }}</span></td>
                            <td><span class="text-gray-800 fw-semibold fs-6">{{ $convocatoria->titulo }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ Str::limit($convocatoria->descripcion, 50) }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'Sin fecha' }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ $convocatoria->lugar ?? 'N/A' }}</span></td>
                            <td>
                                @if($convocatoria->estado === 'borrador')
                                    <span class="badge badge-light-warning fw-bold">Borrador</span>
                                @else
                                    <span class="badge badge-light-success fw-bold">Enviada</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($convocatoria->estado === 'borrador')
                                    <button type="button" wire:click="seleccionarConvocatoria({{ $convocatoria->id }})" class="btn btn-sm btn-light-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_1">
                                        Configurar Sesión
                                    </button>
                                @else
                                    <button type="button" wire:click="seleccionarConvocatoria({{ $convocatoria->id }})" class="btn btn-sm btn-light-info" data-bs-toggle="modal" data-bs-target="#kt_modal_2">
                                        Ver datos de Sesión
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-8 fs-6">No se encontraron convocatorias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: NUEVA SESIÓN -->
    <div class="modal fade" tabindex="-1" id="kt_modal_1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-4">Asignar Configuración de Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:sesiones.sesion-modal />
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 2: VER VERSIÓN GUARDADA -->
    <div class="modal fade" tabindex="-1" id="kt_modal_2" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-4">Datos de Sesión Registrada</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:sesiones.sesion-modal />
                </div>
            </div>
        </div>
    </div>
</div>