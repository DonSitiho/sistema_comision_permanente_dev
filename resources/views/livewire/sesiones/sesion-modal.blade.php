<div>
    @if(!$convocatoriaReciente)
        <div class="card card-flush shadow-sm p-6 h-xl-100 border border-dashed border-gray-300 bg-light-dark">
            <div class="card-body d-flex flex-column flex-center py-10 text-center">
                <div class="mb-3">
                    <i class="ki-duotone ki-information-5 fs-3x text-muted"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                </div>
                <h4 class="text-gray-700 fw-bold">Esperando Convocatoria</h4>
                <p class="text-gray-500 fs-6 max-w-300px mt-2">
                    Completa e inserta el formulario de la izquierda para habilitar la configuración de la sesión correspondiente.
                </p>
            </div>
        </div>
    @else
        <div class="d-flex flex-column flex-column-fluid position-relative" 
            wire:key="container-modal-{{ $convocatoriaReciente->id }}-{{ $esLectura ? 'read' : 'edit' }}">
            <div class="toolbar mb-4" id="kt_toolbar">
                <div class="container-fluid p-0 d-flex flex-stack flex-wrap">
                    <div class="page-title d-flex flex-column me-3">
                        <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
                            <h4><li class="breadcrumb-item text-primary"><i class="bi bi-text-paragraph me-1"></i>Convocatoria: {{ $convocatoriaReciente->tipo_conv }}</li></h4>
                        </ul>
                    </div>
                    <div class="page-title d-flex flex-column me-3">
                        <ul class="breadcrumb breadcrumb-dot fw-bold text-gray-600 fs-7 my-1">
                            <h4><li class="breadcrumb-item text-primary"><i class="bi bi-hash text-primary me-1"></i>Folio: {{ $convocatoriaReciente->folio }}</li></h4>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Modalidad de la Sesión</label>
                <select wire:model.live="tipo" class="form-select form-select-solid" @disabled($esLectura)> 
                    <option value="" selected disabled>Seleccionar</option> 
                    <option value="presencial">Presencial</option> 
                    <option value="virtual">Virtual</option> 
                    <option value="mixta">Mixta</option> 
                </select> 
                @error("tipo") <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4" wire:key="input-desc-box-{{ $convocatoriaReciente->id }}">
                <label class="form-label fw-bold">Descripción de la Sesión</label>
                <div class="input-group input-group-solid">
                    <textarea wire:model="descripcion_sesion" 
                              wire:key="input-desc-{{ $convocatoriaReciente->id }}"
                              rows="3" 
                              class="form-control form-control-solid" 
                              placeholder="Descripción detallada de la sesión ..." 
                              @readonly($esLectura)></textarea>
                </div>
                @error("descripcion_sesion") <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror 
            </div>

            @if($tipo && $tipo !== '#')
                <div class="content d-flex flex-column-fluid mb-4" id="kt_content">
                    <div class="container-fluid p-0">
                        <div class="row g-5">
                            @if ($this->requiereVideoconf()) 
                                <div class="col-xl-8 d-flex flex-column gap-5">
                                    <div class="card card-flush shadow-sm">
                                        <div class="card-header border-0 pt-4 min-h-auto">
                                            <h3 class="card-title align-items-start flex-column mb-0">
                                                <span class="card-label fw-bolder text-gray-900 fs-5">
                                                    <i class="ki-duotone ki-video-camera fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                                    Plataforma de Sesión
                                                </span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-3 pb-4">
                                            <div class="row g-4" data-kt-buttons="true" data-kt-buttons-target="[data-kt-button]">
                                                <!-- Zoom -->
                                                <div class="col-6 col-sm-2">
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-primary d-flex flex-column align-items-center justify-content-center p-3 {{ $plataforma === 'zoom' ? 'active' : '' }}" data-kt-button="true">
                                                        <input class="btn-check" type="radio" wire:model.live="plataforma" value="zoom" name="platform" @disabled($esLectura) />
                                                        <i class="ki-duotone ki-screen fs-2x mb-1 text-primary"></i>
                                                        <span class="fw-bold fs-7 text-gray-800">Zoom</span>
                                                    </label>
                                                </div>
                                                <!-- Google Meet -->
                                                <div class="col-6 col-sm-3">
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-danger d-flex flex-column align-items-center justify-content-center p-3 {{ $plataforma === 'meet' ? 'active' : '' }}" data-kt-button="true">
                                                        <input class="btn-check" type="radio" wire:model.live="plataforma" value="meet" name="platform" @disabled($esLectura) />
                                                        <i class="ki-duotone ki-video fs-2x mb-1 text-danger"></i>
                                                        <span class="fw-bold fs-7 text-gray-800">Google Meet</span>
                                                    </label>
                                                </div>
                                                <!-- Teams -->
                                                <div class="col-6 col-sm-3">
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-info d-flex flex-column align-items-center justify-content-center p-3 {{ $plataforma === 'teams' ? 'active' : '' }}" data-kt-button="true">
                                                        <input class="btn-check" type="radio" wire:model.live="plataforma" value="teams" name="platform" @disabled($esLectura) />
                                                        <i class="ki-duotone ki-people fs-2x mb-1 text-info"></i>
                                                        <span class="fw-bold fs-7 text-gray-800">Microsoft Teams</span>
                                                    </label>
                                                </div>
                                                <!-- Webex -->
                                                <div class="col-6 col-sm-2">
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex flex-column align-items-center justify-content-center p-3 {{ $plataforma === 'webex' ? 'active' : '' }}" data-kt-button="true">
                                                        <input class="btn-check" type="radio" wire:model.live="plataforma" value="webex" name="platform" @disabled($esLectura) />
                                                        <i class="ki-duotone ki-geolocation fs-2x mb-1 text-success"></i>
                                                        <span class="fw-bold fs-7 text-gray-800">Webex</span>
                                                    </label>
                                                </div>
                                                <!-- Otro -->
                                                <div class="col-6 col-sm-2">
                                                    <label class="btn btn-outline btn-outline-dashed btn-active-light-success d-flex flex-column align-items-center justify-content-center p-3 {{ $plataforma === 'otro' ? 'active' : '' }}" data-kt-button="true">
                                                        <input class="btn-check" type="radio" wire:model.live="plataforma" value="otro" name="platform" @disabled($esLectura) />
                                                        <i class="ki-duotone ki-geolocation fs-2x mb-1 text-success"></i>
                                                        <span class="fw-bold fs-7 text-gray-800">Otro</span>
                                                    </label>
                                                </div>
                                            </div>
                                            @error("plataforma") <span class="text-danger small d-block mt-2">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- DETALLES DE CONEXIÓN -->
                                    <div class="card card-flush shadow-sm">
                                        <div class="card-header border-0 pt-4 min-h-auto">
                                            <h3 class="card-title align-items-start flex-column mb-0">
                                                <span class="card-label fw-bolder text-gray-900 fs-5">
                                                    <i class="ki-duotone ki-link fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                                    Detalles de Conexión
                                                </span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-3 pb-4">
                                            <div class="mb-4" wire:key="input-url-box-{{ $convocatoriaReciente->id }}">
                                                <label class="form-label fw-bold text-gray-700">Enlace de la Reunión (URL)</label>
                                                <div class="input-group input-group-solid">
                                                    <input wire:model="enlace_videoconf" 
                                                        wire:key="input-url-{{ $convocatoriaReciente->id }}"
                                                        type="url" class="form-control" 
                                                        placeholder="https://zoom.us/j/..." 
                                                        @readonly($esLectura)> 
                                                </div>
                                                @error("enlace_videoconf") <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror 
                                            </div>
                                            <div class="row g-4">
                                                <div class="col-md-6" wire:key="input-id-reunion-box-{{ $convocatoriaReciente->id }}">
                                                    <label class="form-label fw-bold text-gray-700">ID de la Reunión</label>
                                                    <input wire:model="num_enlace_videoconf" 
                                                        wire:key="input-id-reunion-{{ $convocatoriaReciente->id }}"
                                                        type="text" 
                                                        inputmode="numeric"
                                                        maxlength="15"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                                        class="form-control form-control-solid" 
                                                        placeholder="Ej: 12345687899" 
                                                        @readonly($esLectura)>

                                               
                                                    @error("num_enlace_videoconf") <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                                                </div>
                                                <div class="col-md-6" wire:key="input-code-box-{{ $convocatoriaReciente->id }}">
                                                    <label class="form-label fw-bold text-gray-700">Código de Acceso</label>
                                                    <div class="position-relative">
                                                        <input wire:model="cod_acceso_videoconf" 
                                                            wire:key="input-code-{{ $convocatoriaReciente->id }}"
                                                            type="text" maxlength="8" class="form-control form-control-solid" 
                                                            placeholder="Ej: 1ssN23" 
                                                            @readonly($esLectura)> 
                                                    </div>
                                                    @error("cod_acceso_videoconf") <span class="text-danger small d-block mt-1">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Columna Derecha (Horarios e Invitados) -->
                                <div class="col-xl-4 d-flex flex-column gap-5">
                                    <div class="card card-flush shadow-sm">
                                        <div class="card-header border-0 pt-4 min-h-auto">
                                            <h3 class="card-title align-items-start flex-column mb-0">
                                                <span class="card-label fw-bolder text-gray-900 fs-5">
                                                    <i class="ki-duotone ki-calendar fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                                    Horario Programado
                                                </span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-3 pb-4">
                                            <div class="row g-3 justify-content-center">
                                                <div class="col-6">
                                                    <label class="form-label fw-bold text-gray-700 fs-7">Inicio</label>
                                                    <div class="input-group input-group-sm input-group-solid flatpickr-time-container"
                                                        wire:ignore wire:key="time-ini-virt-{{ $convocatoriaReciente->id }}-{{ $esLectura ? 'read' : 'edit' }}"
                                                        x-data="{ isReadOnly: @js($esLectura), 
                                                            init() { 
                                                                if(!this.isReadOnly) { 
                                                                    flatpickr($el, { 
                                                                        wrap: true, 
                                                                        enableTime: true, 
                                                                        noCalendar: true, 
                                                                        dateFormat: 'H:i', 
                                                                        altInput: true,
                                                                        altFormat: 'H:i \\H\\r\\s\\.',
                                                                        time_24hr: true, 
                                                                        position: 'above', 
                                                                        static: false, 
                                                                        onChange: (selectedDates, dateStr) => {
                                                                            $wire.set('hora_inicio', dateStr);
                                                                        }
                                                                    }); 
                                                                } 
                                                            } 
                                                        }">
                                                        <input class="form-control form-control-solid text-center fw-bold fs-6 px-0" data-input readonly
                                                            wire:model="hora_inicio" value="{{ !empty($hora_inicio) ? (str_contains($hora_inicio, 'Hrs') ? $hora_inicio : $hora_inicio . ' Hrs.') : '00:00 Hrs.' }}" 
                                                            @disabled($esLectura)}/>
                                                        <span class="input-group-text {{ $esLectura ? '' : 'cursor-pointer' }} px-3" data-toggle><i class="bi bi-clock text-primary fs-5"></i></span>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-bold text-gray-700 fs-7">Fin</label>
                                                    <div class="input-group input-group-sm input-group-solid flatpickr-time-container"
                                                        wire:ignore wire:key="time-fin-virt-{{ $convocatoriaReciente->id }}-{{ $esLectura ? 'read' : 'edit' }}"
                                                        x-data="{ isReadOnly: @js($esLectura), 
                                                            init() { 
                                                                if(!this.isReadOnly) { 
                                                                    flatpickr($el, { 
                                                                        wrap: true, 
                                                                        enableTime: true, 
                                                                        noCalendar: true, 
                                                                        dateFormat: 'H:i', 
                                                                        altInput: true,
                                                                        altFormat: 'H:i \\H\\r\\s\\.',
                                                                        time_24hr: true, 
                                                                        position: 'above', 
                                                                        static: false,
                                                                        onChange: (selectedDates, dateStr) => {
                                                                            $wire.set('hora_fin', dateStr);
                                                                        }
                                                                    }); 
                                                                } 
                                                            } 
                                                        }">
                                                        <input class="form-control form-control-solid text-center fw-bold fs-6 px-0" 
                                                            data-input readonly wire:model="hora_fin"
                                                            value="{{ !empty($hora_fin) ? (str_contains($hora_fin, 'Hrs') ? $hora_fin : $hora_fin . ' Hrs.') : '00:00 Hrs.' }}" 
                                                            @disabled($esLectura)}/>
                                                        <span class="input-group-text {{ $esLectura ? '' : 'cursor-pointer' }} px-3" data-toggle><i class="bi bi-clock text-primary fs-5"></i></span>
                                                    </div>
                                                    @error('hora_fin') 
                                                        <span class="text-danger fs-8 fw-bold d-block mt-1">{{ $message }}</span> 
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- INVITADOS VIRTUAL -->
                                    <div class="card card-flush shadow-sm">
                                        <div class="card-header border-0 pt-4 min-h-auto">
                                            <h3 class="card-title align-items-start mb-0">
                                                <span class="card-label fw-bolder text-gray-900 fs-5 me-2">
                                                    <i class="ki-duotone ki-people fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                                    Invitados
                                                </span>
                                                <span class="badge badge-light-primary fw-bolder">{{ count($invitadosSeleccionados) }} Total</span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-3 pb-4 d-flex flex-column gap-3">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary w-100 fw-bold py-3 rounded-3 d-flex align-items-center justify-content-center gap-2" 
                                                    wire:click="abrirModalInvitados">
                                                <i {{ $esLectura ? 'ki-eye' : 'ki-user-plus' }}><span class="path1"></span><span class="path2"></span></i>
                                                {{ $esLectura ? 'Mostrar Invitados' : 'Gestionar Invitados' }}
                                            </button>

                                            @error('invitadosSeleccionados')
                                                <span class="text-danger fs-8 fw-bold">{{ $message }}</span>
                                            @enderror

                                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-3">
                                                <div class="d-flex flex-stack flex-grow-1">
                                                    <div class="fw-semibold">
                                                        <h4 class="text-gray-900 fw-bold fs-6 mb-0">Notificar cambios</h4>
                                                        <div class="fs-7 text-gray-600">Envío automático a lista de invitados</div>
                                                    </div>
                                                    <div class="form-check form-switch form-check-custom form-check-solid ms-3">
                                                        <input class="form-check-input h-20px w-35px" type="checkbox" id="notifyChanges" checked @disabled($esLectura)}/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <!-- MODALIDAD PRESENCIAL -->
                            @else
                                <div class="col-xl-12">
                                    <div class="card card-flush shadow-sm h-100">
                                        <div class="card-header border-0 pt-4 min-h-auto">
                                            <h3 class="card-title align-items-start mb-0">
                                                <span class="card-label fw-bolder text-gray-900 fs-5 me-2">
                                                    <i class="ki-duotone ki-people fs-3 text-primary me-2"><span class="path1"></span><span class="path2"></span></i>
                                                    Invitados
                                                </span>
                                                <span class="badge badge-light-primary fw-bolder">{{ count($invitadosSeleccionados) }} Total</span>
                                            </h3>
                                        </div>
                                        <div class="card-body pt-3 pb-4 d-flex flex-column gap-3">
                                            <button type="button" 
                                                    class="btn btn-sm btn-primary fw-bold py-3 w-200px rounded-3 d-flex align-items-center justify-content-center gap-2" 
                                                    wire:click="abrirModalInvitados">
                                                <i {{ $esLectura ? 'ki-eye' : 'ki-user-plus' }}><span class="path1"></span><span class="path2"></span></i>
                                                {{ $esLectura ? 'Mostrar Invitados' : 'Gestionar Invitados' }}
                                            </button>

                                            @error('invitadosSeleccionados')
                                                <span class="text-danger fs-8 fw-bold">{{ $message }}</span>
                                            @enderror

                                            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-3 w-100">
                                                <div class="d-flex flex-stack flex-grow-1">
                                                    <div class="fw-semibold">
                                                        <h4 class="text-gray-900 fw-bold fs-6 mb-0">Notificar cambios</h4>
                                                        <div class="fs-7 text-gray-600">Envío automático a lista de invitados</div>
                                                    </div>
                                                    <div class="form-check form-switch form-check-custom form-check-solid ms-3">
                                                        <input class="form-check-input h-20px w-35px" type="checkbox" id="notifyChangesPresencial" checked @disabled($esLectura)}/>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-end pt-3 mt-2 border-top">
                @if($esLectura && $convocatoriaReciente->estado != "cancelada")
                    {{--@if($convocatoriaReciente && $convocatoriaReciente->creada_por === Auth::id())
                        <button type="button" 
                            wire:click="cancelarSesionActual" 
                            wire:confirm="¿Estás seguro de que deseas cancelar esta sesión? Esto habilitará la opción de configurar una sesión nueva para esta convocatoria."
                            class="btn btn-sm btn-danger me-2">
                            <i class="ki-duotone ki-trash fs-4 me-1"><span class="path1"></span><span class="path2"></span></i>
                            Cancelar Sesión Actual
                        </button>
                    @endif--}}
                    
                @elseif(!$esLectura)
                    <button type="button" wire:click="submit" class="btn btn-sm btn-primary me-2">
                        Guardar Convocatoria
                    </button>
                @endif
                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Cerrar</button>
            </div>

        </div>
    @endif

    @if($mostrarModalInvitados)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center p-4" 
            style="z-index: 999999 !important; background-color: rgba(0, 0, 0, 0.5); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px);">
            
            <div class="card rounded-4 border-0 shadow-lg p-2 mw-700px w-100 animation-bounce-in">
                <div class="card-header border-0 pb-0 pt-4 px-6 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bolder text-gray-900 fs-3 m-0">
                        {{ $esLectura ? 'Lista de Invitados a la Sesión' : 'Agregar Invitados a la Sesión' }}
                    </h3>
                    <button type="button" 
                            class="btn btn-icon btn-sm btn-active-light-secondary rounded-circle" 
                            wire:click="cerrarModalInvitados">
                        <i class="ki-duotone ki-cross fs-2 text-gray-600"><span class="path1"></span><span class="path2"></span></i>
                    </button>
                </div>

                <div class="card-body py-4 px-6">
                    @if(!$esLectura)
                        <!-- Invitar a Todos -->
                        <div class="bg-light-subtle rounded-3 p-3 mb-4 d-flex align-items-center justify-content-between border border-gray-200">
                            <span class="fw-bold text-gray-800 fs-7 ms-2">Invitar a Todos</span>
                            <button type="button" class="btn btn-sm text-white fw-bold fs-7 px-4 h-35px rounded-3" style="background-color: #0095FF;" wire:click="seleccionarTodos">
                                Seleccionar Todos
                            </button>
                        </div>

                        <!-- Elegir por regiones -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-gray-800 fs-7 mb-2">Por Regiones</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Centro', 'Ciénega', 'Oriente', 'Norte', 'Occidente', 'Sur'] as $reg)
                                    <button type="button" 
                                            wire:click="seleccionarPorRegion('{{ $reg }}')" 
                                            class="btn btn-sm rounded-pill fw-bold fs-8 px-4 py-2 border {{ $regionFiltro === $reg ? 'btn-primary border-0 text-white' : 'btn-light text-gray-700' }}"
                                            style="{{ $regionFiltro === $reg ? 'background-color: #0095FF !important;' : '' }}">
                                        {{ $reg }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Búsqueda Individual -->
                        <div class="mb-5 position-relative">
                            <label class="form-label fw-bold text-gray-800 fs-7 mb-2">Búsqueda Individual</label>
                            <div class="position-relative">
                                <span class="position-absolute top-50 translate-middle-y ms-4">
                                    <i class="ki-duotone ki-magnifier fs-4 text-gray-500"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="buscar_invitado"
                                       class="form-control form-control-solid bg-white border border-gray-300 ps-12 pe-4 h-40px fs-7 w-100 rounded-3"
                                       placeholder="Buscar usuario por nombre"
                                       autocomplete="off" />
                            </div>

                            @if(!empty($buscar_invitado) && count($usuariosBusqueda) > 0)
                                <div class="position-absolute start-0 end-0 bg-white border border-gray-300 rounded-3 shadow-lg mt-1 z-index-3 mh-150px overflow-y-auto">
                                    @foreach($usuariosBusqueda as $user)
                                        <div class="p-2 border-bottom hover-bg-light cursor-pointer d-flex align-items-center justify-content-between" wire:click="toggleInvitado({{ $user->id }})">
                                            <span class="fs-8 fw-bold text-gray-800 ms-2">{{ $user->name }} ({{ $user->email }})</span>
                                            <span class="badge {{ in_array($user->id, $invitadosSeleccionados) ? 'badge-danger' : 'badge-primary' }} fs-9">
                                                {{ in_array($user->id, $invitadosSeleccionados) ? 'Quitar' : 'Agregar' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="border-top pt-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="fw-bolder text-gray-800 fs-7 text-uppercase">
                                Invitados Asignados <span class="badge badge-light-primary ms-1">{{ $invitadosAsignados->count() }} Personas</span>
                            </span>
                        </div>

                        <div class="mh-250px overflow-y-auto pe-1">
                            @if($invitadosAsignados->isEmpty())
                                <div class="text-center py-6 text-gray-500 fs-7">No hay invitados asignados a esta sesión.</div>
                            @else
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-2">
                                    @foreach($invitadosAsignados as $invitado)
                                        <div class="col" wire:key="invitado-{{ $invitado->id }}">
                                            <div class="d-flex align-items-center justify-content-between p-2 rounded-3 border border-gray-200 bg-white hover-elevation-1 transition-all">
                                                <div class="d-flex align-items-center gap-2 text-truncate">
                                                    <div class="symbol symbol-30px symbol-circle bg-light-primary text-primary fw-bolder fs-8 d-flex align-items-center justify-content-center">
                                                        {{ strtoupper(substr($invitado->name, 0, 2)) }}
                                                    </div>
                                                    <div class="d-flex flex-column text-truncate">
                                                        <span class="fw-bold text-gray-800 fs-8 text-truncate">{{ $invitado->name }}</span>
                                                        <span class="text-gray-500 fs-9 text-truncate">{{ $invitado->cargo ?? $invitado->email }}</span>
                                                    </div>
                                                </div>
                                                @if(!$esLectura)
                                                    <button type="button" class="btn btn-icon btn-xs btn-active-light-danger rounded-circle flex-shrink-0 ms-1" wire:click="toggleInvitado({{ $invitado->id }})" title="Quitar">
                                                        <i class="ki-duotone ki-cross fs-7"><span class="path1"></span><span class="path2"></span></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-footer border-0 pt-0 pb-4 px-6 d-flex align-items-center justify-content-end gap-3">
                    <button type="button" 
                            class="btn btn-sm btn-light border-0 text-gray-700 fw-bold fs-7 px-5 h-40px rounded-3" 
                            wire:click="cerrarModalInvitados">
                        {{ $esLectura ? 'Cerrar' : 'Cancelar' }}
                    </button>
                    @if(!$esLectura)
                        <button type="button" 
                                class="btn btn-sm text-white fw-bold fs-7 px-6 h-40px rounded-3" 
                                style="background-color: #0095FF;" 
                                wire:click="cerrarModalInvitados">
                            Confirmar Invitados
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
    <style>
        .animation-bounce-in {
            animation: bounceInOverlay 0.25s ease-out forwards;
        }

        @keyframes bounceInOverlay {
            from { opacity: 0; transform: scale(0.92); }
            to { opacity: 1; transform: scale(1); }
        }

        .flatpickr-time-container {
            position: relative !important;
            overflow: visible !important;
        }

        .flatpickr-calendar.hasTime.noCalendar {
            z-index: 999999 !important;
            width: 170px !important;
            padding: 14px 10px !important;
            border-radius: 0.85rem !important;
            background-color: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(8px);
            border: 1px solid #e1e3ea !important;
            box-shadow: 0 15px 35px rgba(24, 28, 50, 0.12) !important;
            margin-bottom: 12px !important;
            animation: metronicBounceIn 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.15) forwards;
        }

        .flatpickr-calendar.hasTime.noCalendar::after {
            content: "" !important;
            display: block !important;
            position: absolute !important;
            bottom: -6px !important;
            left: 50% !important;
            transform: translateX(-50%) rotate(45deg) !important;
            width: 12px !important;
            height: 12px !important;
            background-color: #ffffff !important;
            border-right: 1px solid #e1e3ea !important;
            border-bottom: 1px solid #e1e3ea !important;
            z-index: -1 !important;
        }

        @keyframes metronicBounceIn {
            from { opacity: 0; transform: scale(0.9) translateY(12px); }
            to { opacity: 1; transform: scale(1); }
        }

        .flatpickr-calendar::before {
            display: none !important;
        }

        .flatpickr-time {
            height: 38px !important;
            line-height: 38px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .flatpickr-time input.flatpickr-hour,
        .flatpickr-time input.flatpickr-minute {
            font-family: inherit !important;
            font-size: 1.35rem !important;
            font-weight: 800 !important;
            color: #181c32 !important;
            background-color: #f9f9f9 !important;
            border-radius: 0.5rem !important;
            max-width: 40px !important;
            height: 32px !important;
            border: 1px solid transparent !important;
            transition: all 0.2s ease !important;
        }

        .flatpickr-time input.flatpickr-hour:hover,
        .flatpickr-time input.flatpickr-minute:hover,
        .flatpickr-time input.flatpickr-hour:focus,
        .flatpickr-time input.flatpickr-minute:focus {
            background-color: #f1faff !important;
            border-color: #c0dbff !important;
            color: #ca9e0e !important;
        }

        .flatpickr-time .flatpickr-time-separator {
            color: #a1a5b7 !important;
            font-weight: 700 !important;
            font-size: 1.35rem !important;
            margin: 0 4px !important;
        }

        .flatpickr-time .arrowUp, 
        .flatpickr-time .arrowDown {
            width: 50% !important;
            height: 35% !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: background 0.2s ease !important;
        }

        .flatpickr-time .arrowUp::after { 
            border-bottom-color: #a1a5b7 !important; 
            border-width: 0 4px 5px 4px !important;
            transition: border-color 0.2s ease !important;
        }
    </style>
@endpush