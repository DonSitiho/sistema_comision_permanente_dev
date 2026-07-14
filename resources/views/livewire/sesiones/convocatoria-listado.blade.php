<div>
    <!-- Buscador por folio, título y fecha -->
    <div class="d-flex justify-content-end w-100 mb-5">
        <div class="position-relative w-25">
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
        <!--<div class="card-header pt-2 ps-0">
            <h3 class="card-title fw-bold text-gray-800 fs-2">Listado de Convocatorias</h3>
        </div>-->
        <div class="card-header pt-2 ps-0">
            <h3 class="card-title fw-bold text-gray-800 fs-2">
                {{ $alcance === 'propias' ? 'Mis Convocatorias y Sesiones' : 'Listado General de Convocatorias' }}
            </h3>
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
                        <th class="min-w-100px">Estado Convocatoria</th>
                        <th class="min-w-100px">Estado Sesión</th>
                        <th class="min-w-100px text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($convocatorias as $convocatoria)
                        <tr wire:key="row-convocatoria-{{ $convocatoria->id }}">
                            <td><span class="text-gray-800 fw-bold fs-6">{{ $convocatoria->folio }}</span></td>
                            <td><span class="text-gray-800 fw-semibold fs-6">{{ $convocatoria->titulo }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ Str::limit($convocatoria->descripcion, 50) }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'Sin fecha' }}</span></td>
                            <td><span class="text-gray-800 fs-6">{{ $convocatoria->lugar ?? 'N/A' }}</span></td>
                            <td>
                                @if($convocatoria->estado === 'borrador')
                                    <span class="badge badge-light-warning fw-bold">Borrador</span>
                                @elseif($convocatoria->estado === 'enviada')
                                    <span class="badge badge-light-success fw-bold">Enviada</span>
                                @elseif($convocatoria->estado === 'pospuesta')
                                    <span class="badge badge-light-info fw-bold">Pospuesta</span>
                                @elseif($convocatoria->estado === 'cancelada')
                                    <span class="badge badge-light-danger fw-bold">Cancelada</span>
                                @endif
                            </td>
                            <td>
                                @if($convocatoria->sesion)
                                    @if($convocatoria->sesion->estado === 'convocada')
                                        <span class="badge badge-light-success fw-bold">Convocada</span>
                                    @elseif($convocatoria->sesion->estado === 'en_curso')
                                        <span class="badge badge-light-info fw-bold">En Curso</span>
                                    @elseif($convocatoria->sesion->estado === 'realizada')
                                        <span class="badge badge-light-primary fw-bold">Realizada</span>
                                    @elseif($convocatoria->sesion->estado === 'cancelada')
                                        <span class="badge badge-light-danger fw-bold">Cancelada</span>
                                    @endif
                                @else
                                    <span class="text-muted fs-7 italic">Sin Sesión</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if($convocatoria->estado === 'cancelada')
                                    <span class="text-muted fs-7 fw-bold"><i class="ki-duotone ki-lock fs-6 text-muted me-1"></i> Convocatoria Cancelada</span> 
                                @else
                                    @if(!$convocatoria->sesion || ($convocatoria->sesion->estado !== 'realizada' && $convocatoria->sesion->estado !== 'en_curso'))
                                        <button type="button" 
                                            wire:click="prepararOpciones({{ $convocatoria->id }})" 
                                            class="btn btn-sm btn-light-info fw-bold">
                                            @if($convocatoria->creada_por === Auth::id())
                                                Configurar Sesión
                                            @else
                                                Consultar Detalles
                                            @endif
                                        </button>
                                    @endif
                                    @if($convocatoria->creada_por === Auth::id() && $convocatoria->sesion && $convocatoria->sesion->estado === 'realizada')
                                        <a href="{{ route('documentos', $convocatoria->sesion->id) }}" 
                                        class="btn btn-sm btn-light-success fw-bold d-inline-flex align-items-center">
                                            <i class="ki-duotone ki-cloud-upload fs-6 me-1">
                                                <span class="path1"></span><span class="path2"></span>
                                            </i>
                                            Subir Documento
                                        </a>
                                    @endif
                                    @if($convocatoria->creada_por === Auth::id() && $convocatoria->sesion && $convocatoria->sesion->estado !== 'realizada' && $convocatoria->sesion->estado !== 'en_curso')
                                        <button type="button" wire:click="$dispatch('cargar-convocatoria-a-cancelar', { id: {{ $convocatoria->id }} })" 
                                            wire:confirm="¿Estás seguro de que deseas cancelar de forma permanente esta convocatoria?" class="btn btn-sm btn-light-danger">
                                            Cancelar Convocatoria
                                        </button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-8 fs-6">No se encontraron convocatorias registradas.</td>
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

    <!-- MODAL 2: VER SESIÓN GUARDADA -->
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

    <!-- MODAL 3: POSPONER SESIÓN GUARDADA -->
    <div class="modal fade" tabindex="-1" id="kt_modal_3" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-4">Posponer Sesión</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:sesiones.convocatoria-modal />
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL 4: MENÚ GENERAL DE ACCIONES -->
    <div class="modal fade" tabindex="-1" id="kt_modal_4" wire:ignore.self>    
        <div class="modal-dialog modal-dialog-centered mw-550px">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold fs-4 text-gray-800">
                        Opciones de Convocatoria Folio: <span class="text-primary">{{ $convocatoriaSeleccionada?->folio }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-8">
                    @if($convocatoriaSeleccionada)
                        <!-- Datos de la convocatoria -->
                        <div class="bg-light-success rounded p-5 mb-6 border border-success border-dashed text-start">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">TÍTULO</span>
                                    <span class="text-gray-800 fw-semibold fs-6">{{ $convocatoriaSeleccionada->titulo }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">DESCRIPCIÓN</span>
                                    <span class="text-gray-800 fw-bold fs-5">{{ Str::limit($convocatoriaSeleccionada->descripcion, 40) }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">FECHA Y HORA</span>
                                    <span class="text-gray-800 fs-6">{{ $convocatoriaSeleccionada->fecha_sesion ? $convocatoriaSeleccionada->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">LUGAR</span>
                                    <span class="text-gray-800 fs-6">{{ $convocatoriaSeleccionada->lugar ?? 'Sin definir' }}</span>
                                </div>
                            </div>
                        </div>
                        <!-- Opciones para sesiones y convocatorias -->
                        <div class="d-flex flex-column gap-4">
                            @if(!$convocatoriaSeleccionada->sesion || $convocatoriaSeleccionada->estado === 'borrador')
                                <!-- Configurar Datos de Sesión -->
                                @if($convocatoriaSeleccionada->creada_por === Auth::id())
                                    <button type="button" wire:click="ejecutarConfigurar({{ $convocatoriaSeleccionada->id }})" 
                                        class="btn btn-light-primary py-4 w-100 fw-bold fs-5 text-start ps-8">
                                        <i class="ki-duotone ki-notepad fs-1 text-primary me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Configurar Datos de Sesión Nueva
                                    </button>
                                @endif
                            @else
                                <!-- Ver Datos de Sesión Registrada -->
                                <button type="button" wire:click="ejecutarVerDatos({{ $convocatoriaSeleccionada->id }})" 
                                    class="btn btn-light-info py-4 w-100 fw-bold fs-5 text-start ps-8">
                                    <i class="ki-duotone ki-eye fs-1 text-info me-3"><span class="path1"></span><span class="path2"></span></i>
                                    Ver Datos de Sesión Registrada
                                </button>

                                @if($convocatoriaSeleccionada->creada_por === Auth::id())
                                    <!-- Posponer Fecha / Hora -->
                                    <button type="button" wire:click="ejecutarPosponer({{ $convocatoriaSeleccionada->id }})" 
                                            class="btn btn-light-warning py-4 w-100 fw-bold fs-5 text-start ps-8">
                                        <i class="ki-duotone ki-time fs-1 text-warning me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Posponer Fecha / Hora
                                    </button>

                                    <!-- Cancelar Sesión Actual -->
                                    <button type="button" 
                                            wire:click="ejecutarCancelarSesion({{ $convocatoriaSeleccionada->id }})" 
                                            wire:confirm="¿Estás seguro de que deseas cancelar esta sesión? Esto habilitará la opción de configurar una sesión nueva."
                                            class="btn btn-light-danger py-4 w-100 fw-bold fs-5 text-start ps-8"
                                            data-bs-dismiss="modal">
                                        <i class="ki-duotone ki-trash fs-1 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Cancelar Sesión Actual
                                    </button>                          
                                @endif
                            @endif
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="text-muted mt-3 fs-7 fw-semibold">Sincronizando información de la convocatoria...</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar Menú</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', () => {
        window.cerrarModalesMetronic = () => {
            document.querySelectorAll('.modal').forEach(modalEl => {
                const modalInstancia = bootstrap.Modal.getInstance(modalEl);
                if (modalInstancia) {
                    modalInstancia.hide();
                }
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                modalEl.style.zIndex = '';
                modalEl.removeAttribute('aria-modal');
                modalEl.removeAttribute('role');
                modalEl.removeAttribute('inert');
                modalEl.setAttribute('aria-hidden', 'true');
            });

            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

            document.body.style.overflow = 'auto';
            document.body.style.paddingRight = '0px';
            document.body.classList.remove('modal-open');
            
            if (document.activeElement && document.activeElement !== document.body) {
                document.activeElement.blur();
            }
        };

        Livewire.on('refresh-listado-convocatorias', window.cerrarModalesMetronic);
        Livewire.on('refreshTable', window.cerrarModalesMetronic);
        Livewire.on('mostrar-menu-opciones', () => {
            const modalPadreEl = document.getElementById('kt_modal_4');
            if (modalPadreEl) {
                modalPadreEl.removeAttribute('inert');
                modalPadreEl.removeAttribute('aria-hidden');
                modalPadreEl.style.zIndex = '';
                
                const instanciaPadre = new bootstrap.Modal(modalPadreEl, { focus: false });
                instanciaPadre.show();
            }
        });

        Livewire.on('abrir-submodal-seguro', (event) => {
            const targetId = event.targetModal || event[0].targetModal;
            const subModalEl = document.getElementById(targetId);
            const modalPadreEl = document.getElementById('kt_modal_4');
            
            if (!subModalEl) return;

            if (modalPadreEl && modalPadreEl.classList.contains('show')) {
                const instanciaPadre = bootstrap.Modal.getInstance(modalPadreEl);
                if (instanciaPadre) {
                    instanciaPadre.hide();
                }
                
                modalPadreEl.addEventListener('hidden.bs.modal', function transicionCierre() {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());

                    subModalEl.removeAttribute('inert');
                    subModalEl.removeAttribute('aria-hidden');

                    const subModal = new bootstrap.Modal(subModalEl, { focus: false });
                    subModal.show();

                    modalPadreEl.removeEventListener('hidden.bs.modal', transicionCierre);
                });
            } else {
                subModalEl.removeAttribute('inert');
                subModalEl.removeAttribute('aria-hidden');
                const subModal = new bootstrap.Modal(subModalEl, { focus: false });
                subModal.show();
            }
        });
    });
</script>