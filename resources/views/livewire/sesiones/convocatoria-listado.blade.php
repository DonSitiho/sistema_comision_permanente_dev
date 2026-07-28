<div>
    <style>
        /* Estilo del botón de filtro */
        .select-inactivo {
            background-color: #f5f8fa !important;
            color: #5e6278 !important;
            font-weight: 600 !important;
        }

        .select-activo {
            background-color: rgba(31, 56, 100, 0.12) !important;
            color: #1F3864 !important;
            border: 1px solid rgba(31, 56, 100, 0.25) !important;
            font-weight: 700 !important;
        }
    </style>

    <div class="d-flex flex-wrap flex-stack mb-6 gap-3">
        <h1 class="fw-bold text-gray-900 my-0 fs-2">
            {{ $alcance === 'propias' ? 'Mis Convocatorias y Sesiones' : 'Listado General de Convocatorias' }}
        </h1>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <!-- Buscador -->
            <div class="position-relative">
                <span class="position-absolute top-50 translate-middle-y ms-4">
                    <i class="ki-duotone ki-magnifier fs-3 text-gray-500">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                </span>
                <input type="text" 
                    wire:model.live.debounce.300ms="buscar"
                    class="form-control form-control-solid ps-12 pe-4 h-40px fs-7 w-200px w-md-230px border-0"
                    placeholder="Buscar convocatoria"
                    autocomplete="off" />
            </div>

            <!-- Filtro por estatus -->
            <div class="position-relative" x-data="{ open: false }" @click.outside="open = false">
                <button type="button" 
                        @click="open = !open" 
                        class="btn h-40px fs-7 rounded-3 d-flex align-items-center justify-content-between px-4 transition-all border-0 w-220px {{ !empty($filtroEstatus) ? 'select-activo' : 'select-inactivo' }}">
                    <span class="text-truncate fw-semibold">
                        @if(empty($filtroEstatus)) 🌐 Todas las convocatorias
                        @elseif($filtroEstatus === 'mis_convocatorias') ⭐ Mis Convocatorias
                        @elseif($filtroEstatus === 'borrador') 📝 Borrador
                        @elseif($filtroEstatus === 'enviada') 📤 Enviada
                        @elseif($filtroEstatus === 'pospuesta') ⏳ Pospuesta
                        @elseif($filtroEstatus === 'cerrada') 🔒 Cerrada
                        @elseif($filtroEstatus === 'cancelada') ❌ Cancelada
                        @endif
                    </span>
                    <i class="ki-duotone ki-down fs-5 ms-2 flex-shrink-0" :class="{ 'rotate-180': open }"></i>
                </button>

                <div x-show="open" 
                    x-cloak 
                    class="position-absolute end-0 bg-white p-2 shadow-lg rounded-3 mt-1 fs-7 border-0 min-w-100"
                    style="z-index: 9999; display: none; white-space: nowrap;">
                    
                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === '' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('')" @click="open = false">
                        🌐 Todas las convocatorias
                    </button>

                    @if($alcance !== 'propias')
                        <button type="button" 
                                class="dropdown-item rounded py-2 px-3 fw-bold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === 'mis_convocatorias' ? 'bg-light-primary text-primary' : '' }}" 
                                wire:click="seleccionarEstatus('mis_convocatorias')"  @click="open = false">
                            ⭐ Mis Convocatorias
                        </button>
                    @endif

                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === 'borrador' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('borrador')" @click="open = false">
                        📝 Borrador
                    </button>

                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === 'enviada' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('enviada')" @click="open = false">
                        📤 Enviada
                    </button>

                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === 'pospuesta' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('pospuesta')" @click="open = false">
                        ⏳ Pospuesta
                    </button>

                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary mb-1 {{ $filtroEstatus === 'cerrada' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('cerrada')" @click="open = false">
                        🔒 Cerrada
                    </button>

                    <button type="button" 
                            class="dropdown-item rounded py-2 px-3 fw-semibold w-100 text-start text-gray-700 btn-active-light-primary {{ $filtroEstatus === 'cancelada' ? 'bg-light-primary text-primary' : '' }}" 
                            wire:click="seleccionarEstatus('cancelada')" @click="open = false">
                        ❌ Cancelada
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-6 mb-8">
        @forelse($convocatorias as $convocatoria)
            <div class="col-md-6 col-xl-4" wire:key="card-convocatoria-{{ $convocatoria->id }}">
                <div class="card card-custom h-100 shadow-sm border border-gray-200 hover-elevation-2 transition-all">

                    <div class="card-body p-6 d-flex flex-column justify-content-between">
                        <div>
                            <!-- Header de la tarjeta -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="text-gray-500 fw-bold fs-7">
                                    Folio: <strong class="text-gray-800">#{{ $convocatoria->folio }}</strong>
                                </span>
                               
                                <div>
                                    @if($convocatoria->estado === 'cancelada')
                                        <span class="badge badge-light-danger fs-8" title="Convocatoria Cancelada">
                                            <i class="ki-duotone ki-lock fs-7 text-danger me-1"></i> Cancelada
                                        </span>
                                    @else
                                        <button type="button" 
                                                wire:click.prevent="prepararOpciones({{ $convocatoria->id }})" 
                                                class="btn btn-sm btn-icon border-0" 
                                                {{--style="background-color: rgba(31, 56, 100, 0.08) !important;"--}}
                                                title="Opciones">
                                            <i class="ki-duotone ki-dots-horizontal fs-2" style="color: #1F3864 !important;">
                                                <span class="path1" style="color: #1F3864 !important;"></span>
                                                <span class="path2" style="color: #1F3864 !important;"></span>
                                                <span class="path3" style="color: #1F3864 !important;"></span>
                                            </i>
                                        </button>
                                    @endif
                                </div>       
                            </div>

                            <h3 class="fs-4 fw-bold mb-2 text-truncate" title="{{ $convocatoria->titulo }}">
                                <span class="titulo-azul-custom">{{ $convocatoria->titulo }}</span>
                            </h3>

                            <div class="d-flex align-items-center mb-4">
                                @if(Str::contains(Str::lower($convocatoria->tipo_conv), ['virtual', 'mixta']))
                                    <i class="ki-duotone ki-screen fs-4 me-2" style="color: #1F3864 !important;">
                                        <span class="path1" style="color: #1F3864 !important;"></span>
                                        <span class="path2" style="color: #1F3864 !important;"></span>
                                        <span class="path3" style="color: #1F3864 !important;"></span>
                                        <span class="path4" style="color: #1F3864 !important;"></span>
                                    </i>
                                @else
                                    <i class="ki-duotone ki-document fs-4 me-2" style="color: #1F3864 !important;">
                                        <span class="path1" style="color: #1F3864 !important;"></span>
                                        <span class="path2" style="color: #1F3864 !important;"></span>
                                    </i>
                                @endif
                               <span class="text-gray-700 fw-bold fs-7">
                                    {{ $convocatoria->tipo_conv }} / {{ $convocatoria->sesion?->tipo ?? 'Sin sesión' }}
                                </span>
                            </div>

                            <div class="d-flex flex-column gap-2 mb-5">
                                <div class="d-flex align-items-center text-gray-600 fs-7">
                                    <i class="ki-duotone ki-calendar fs-5 me-2 text-gray-500">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <span>{{ $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'Sin fecha asignada' }}</span>
                                </div>

                                <div class="d-flex align-items-center text-gray-600 fs-7">
                                    <i class="ki-duotone ki-geolocation fs-5 me-2 text-gray-500">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>
                                    <span class="text-truncate">{{ $convocatoria->lugar ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-gray-100 gap-2">
                            <div>
                                @if($convocatoria->estado === 'borrador')
                                    <span class="badge badge-light-warning fw-bold px-3 py-2">Borrador</span>
                                @elseif($convocatoria->estado === 'enviada')
                                    <span class="badge badge-light-success fw-bold px-3 py-2">Enviada</span>
                                @elseif($convocatoria->estado === 'pospuesta')
                                    <span class="badge badge-light-info fw-bold px-3 py-2">Pospuesta</span>
                                @elseif($convocatoria->estado === 'cerrada')
                                    <span class="badge badge-light-secondary fw-bold px-3 py-2">Cerrada</span>
                                @elseif($convocatoria->estado === 'cancelada')
                                    <span class="badge badge-light-danger fw-bold px-3 py-2">Cancelada</span>
                                @endif
                            </div>

                            <div>
                                @if($convocatoria->sesion)
                                    @if($convocatoria->sesion->estado === 'convocada')
                                        <span class="badge badge-light-success fw-bold px-3 py-2">Convocada</span>
                                    @elseif($convocatoria->sesion->estado === 'en_curso')
                                        <span class="badge badge-light-info fw-bold px-3 py-2">En Curso</span>
                                    @elseif($convocatoria->sesion->estado === 'realizada')
                                        <span class="badge badge-light-primary fw-bold px-3 py-2">Realizada</span>
                                    @elseif($convocatoria->sesion->estado === 'cancelada')
                                        <span class="badge badge-light-danger fw-bold px-3 py-2">Sesión Cancelada</span>
                                    @endif
                                @else
                                    <span class="text-muted fs-8 italic">Sin Sesión</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card card-dashed p-10 text-center">
                    <div class="text-gray-500 fs-6 fw-semibold">
                        No se encontraron convocatorias registradas con los criterios de búsqueda.
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- MODAL 1: NUEVA SESIÓN -->
    <div class="modal fade" tabindex="-1" id="kt_modal_1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-1000px">
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
        <div class="modal-dialog modal-dialog-centered mw-1000px">
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
                        Opciones de Convocatoria Folio: <span style="color: #1F3864;">{{ $convocatoriaSeleccionada?->folio }}</span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-8">
                    @if($convocatoriaSeleccionada)
                        <div class="bg-light-success rounded p-5 mb-6 border border-success border-dashed text-start">
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">CONVOCATORIA</span>
                                    <span class="text-gray-800 fw-semibold fs-6">{{ $convocatoriaSeleccionada->tipo_conv }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">TÍTULO</span>
                                    <span class="text-gray-800 fw-semibold fs-6">{{ $convocatoriaSeleccionada->titulo }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">LUGAR</span>
                                    <span class="text-gray-800 fs-6">{{ $convocatoriaSeleccionada->lugar ?? 'Sin definir' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-muted d-block fs-7 fw-bold">FECHA Y HORA</span>
                                    <span class="text-gray-800 fs-6">{{ $convocatoriaSeleccionada->fecha_sesion ? $convocatoriaSeleccionada->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'N/A' }}</span>
                                </div>
                                <div class="col-sm-12">
                                    <span class="text-muted d-block fs-7 fw-bold">DESCRIPCIÓN</span>
                                    <span class="text-gray-800 fw-bold fs-5">{{ Str::limit($convocatoriaSeleccionada->descripcion, 40) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column gap-4">
                            @if(!$convocatoriaSeleccionada->sesion || $convocatoriaSeleccionada->estado === 'borrador')
                                @if($convocatoriaSeleccionada->creada_por === Auth::id())
                                    <button type="button" wire:click="ejecutarConfigurar({{ $convocatoriaSeleccionada->id }})" 
                                        class="btn btn-light-primary py-4 w-100 fw-bold fs-5 text-start ps-8">
                                        <i class="ki-duotone ki-notepad fs-1 text-primary me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Configurar Datos de Sesión Nueva
                                    </button>
                                @endif
                            @else
                                <button type="button" wire:click="ejecutarVerDatos({{ $convocatoriaSeleccionada->id }})" 
                                    class="btn btn-light-info py-4 w-100 fw-bold fs-5 text-start ps-8">
                                    <i class="ki-duotone ki-eye fs-1 text-info me-3"><span class="path1"></span><span class="path2"></span></i>
                                    Ver Datos de Sesión Registrada
                                </button>

                                @if($convocatoriaSeleccionada->creada_por === Auth::id())
                                    <button type="button" wire:click="ejecutarPosponer({{ $convocatoriaSeleccionada->id }})" 
                                            class="btn btn-light-warning py-4 w-100 fw-bold fs-5 text-start ps-8">
                                        <i class="ki-duotone ki-time fs-1 text-warning me-3"><span class="path1"></span><span class="path2"></span></i>
                                        Posponer Fecha / Hora
                                    </button>

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

                            @if($convocatoriaSeleccionada->creada_por === Auth::id() && $convocatoriaSeleccionada->sesion && $convocatoriaSeleccionada->sesion->estado === 'realizada')
                                <a href="{{ route('documentos', $convocatoriaSeleccionada->sesion->id) }}" 
                                   class="btn btn-light-success py-4 w-100 fw-bold fs-5 text-start ps-8">
                                    <i class="ki-duotone ki-cloud-upload fs-1 text-success me-3"><span class="path1"></span><span class="path2"></span></i>
                                    Subir Documento
                                </a>
                            @endif

                            @if($convocatoriaSeleccionada->creada_por === Auth::id() && $convocatoriaSeleccionada->sesion && $convocatoriaSeleccionada->sesion->estado !== 'realizada' && $convocatoriaSeleccionada->sesion->estado !== 'en_curso')
                                <button type="button" 
                                        wire:click="$dispatch('cargar-convocatoria-a-cancelar', { id: {{ $convocatoriaSeleccionada->id }} })" 
                                        wire:confirm="¿Estás seguro de que deseas cancelar de forma permanente esta convocatoria?" 
                                        class="btn btn-light-danger py-4 w-100 fw-bold fs-5 text-start ps-8"
                                        data-bs-dismiss="modal">
                                    <i class="ki-duotone ki-cross-circle fs-1 text-danger me-3"><span class="path1"></span><span class="path2"></span></i>
                                    Cancelar Convocatoria
                                </button>
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
                
                let instanciaPadre = bootstrap.Modal.getInstance(modalPadreEl);
                if (!instanciaPadre) {
                    instanciaPadre = new bootstrap.Modal(modalPadreEl, { focus: false });
                }
                instanciaPadre.show();
            }
        });

        Livewire.on('abrir-submodal-seguro', (event) => {
            const targetId = event.targetModal || (event[0] && event[0].targetModal);
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