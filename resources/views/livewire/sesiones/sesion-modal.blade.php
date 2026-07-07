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
        <div class="card card-flush shadow-sm p-6 h-xl-100">
            <div class="card-header pt-2 ps-0">
                <h4 class="card-title fw-bold text-success fs-4">Datos de la Convocatoria</h4>
            </div>
            
            <!-- Datos capturados para convocatoria -->
            <div class="bg-light-success rounded p-5 mb-6 border border-success border-dashed">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <span class="text-muted d-block fs-7 fw-bold">FOLIO ASIGNADO</span>
                        <span class="text-gray-800 fw-bold fs-5">{{ $convocatoriaReciente->folio }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block fs-7 fw-bold">TÍTULO</span>
                        <span class="text-gray-800 fw-semibold fs-6">{{ $convocatoriaReciente->titulo }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block fs-7 fw-bold">FECHA Y HORA</span>
                        <span class="text-gray-800 fs-6">{{ $convocatoriaReciente->fecha_sesion ? $convocatoriaReciente->fecha_sesion->format('d/m/Y - H:i') . ' Hrs.' : 'N/A' }}</span>
                    </div>
                    <div class="col-sm-6">
                        <span class="text-muted d-block fs-7 fw-bold">LUGAR</span>
                        <span class="text-gray-800 fs-6">{{ $convocatoriaReciente->lugar ?? 'Sin definir' }}</span>
                    </div>
                </div>
            </div>

            <!-- FORMULARIO DE SESIÓN -->
            <div class="separator separator-dashed my-5"></div>
            
            <h4 class="card-title fw-bold text-success fs-4">Agregar Datos de Sesión</h4>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Modalidad de la Sesión</label>
                <select wire:model.live="tipo" class="form-select" @disabled($esLectura)> 
                    <option value="presencial">Presencial</option> 
                    <option value="virtual">Virtual</option> 
                    <option value="mixta">Mixta</option> 
                </select> 
            </div>
          
            @if ($this->requiereVideoconf()) 
                <div class="mb-4"> 
                    <label class="form-label fw-bold">Plataforma</label> 
                    <select wire:model="plataforma" class="form-select" @disabled($esLectura)> 
                        <option value="">Seleccionar plataforma...</option> 
                        <option value="zoom">Zoom</option> 
                        <option value="meet">Google Meet</option> 
                        <option value="webex">Webex</option> 
                        <option value="teams">Microsoft Teams</option> 
                        <option value="otro">Otro</option> 
                    </select> 
                    @error("plataforma") <span class="text-danger small">{{ $message }}</span> @enderror
                </div> 

                <div class="mb-4"> 
                    <label class="form-label fw-bold">Enlace de Acceso a la Videoconferencia</label> 
                    <input wire:model="enlace_videoconf" type="url" class="form-control" placeholder="https://zoom.us/j/..." @readonly($esLectura)> 
                    @error("enlace_videoconf") <span class="text-danger small">{{ $message }}</span> @enderror 
                </div> 
            @endif 

            {{-- El botón de guardar solo aparecerá si es una sesión nueva (Borrador) --}}
            @if(!$esLectura)
                <div class="text-end pt-4 mt-5">
                    <button type="button" wire:click="submit" data-bs-dismiss="modal" class="btn btn-success">
                        Asignar y Enviar Sesión
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>