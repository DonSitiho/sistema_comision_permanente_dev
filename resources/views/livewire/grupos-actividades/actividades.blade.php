<div class="card card-flush shadow-xs border border-gray-200 rounded-4 bg-white mb-6">
    <div class="card-header border-0 pt-5 pb-2 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-list-task fs-3 text-gray-700"></i>
            <h3 class="card-title fw-bolder text-gray-900 fs-4 m-0 me-2">Actividades Individuales</h3>
            <span class="badge badge-light-secondary text-gray-700 fw-bold fs-8 rounded-pill px-2 py-1">
                {{ count($actividades ?? []) }}
            </span>
        </div>

        <button type="button" 
                wire:click="agregarActividad" 
                class="btn btn-sm btn-light-primary text-primary fw-bold px-4 py-2 rounded-3 border border-primary-subtle fs-8">
            <i class="bi bi-plus-lg me-1"></i> Añadir Actividad
        </button>
    </div>

    <div class="card-body pt-3">
        <div class="d-flex flex-column gap-4">
            @foreach($actividades as $index => $actividad)
                <div class="p-4 pt-5 rounded-3 border border-gray-300 bg-light-subtle position-relative" wire:key="actividad-{{ $index }}">
                    
                    @if(count($actividades ?? []) > 1 || isset($actividad['id']))
                        <button type="button" 
                                wire:click="removeActividad({{ $index }})" 
                                class="btn btn-icon btn-xs btn-active-light-danger rounded-circle position-absolute top-0 end-0 mt-2 me-2" 
                                title="Eliminar actividad">
                            <i class="bi bi-x-lg text-danger fs-8"></i>
                        </button>
                    @endif

                    <div class="row g-4 align-items-end">
                        
                        <div class="col-lg-4">
                            <label class="form-label fw-bolder text-gray-700 fs-9 text-uppercase mb-2">Descripción de la actividad</label>
                            <textarea wire:model="actividades.{{ $index }}.descripcion" 
                                      rows="2" 
                                      class="form-control form-control-solid bg-white border border-gray-300 rounded-3 fs-8" 
                                      placeholder="Define la actividad a realizar"></textarea>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-bolder text-gray-700 fs-9 text-uppercase mb-2">Responsable Asignado</label>
                            <div class="position-relative">
                                <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-gray-500">
                                    <i class="bi bi-person fs-6"></i>
                                </span>
                                <select wire:model="actividades.{{ $index }}.responsable_id" 
                                        class="form-select form-select-solid bg-white border border-gray-300 rounded-3 fs-8 ps-10">
                                    <option value="">Sin asignar (Seleccionar después)...</option>
                                    @foreach($invitados as $invitado)
                                        <option value="{{ $invitado->user_id }}">
                                            {{ $invitado->user->name ?? 'Usuario #' . $invitado->user_id }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label class="form-label fw-bolder text-gray-700 fs-9 text-uppercase mb-2">Fecha de Límite de Entrega</label>
                            <input type="date" 
                                   min="{{ $fechaMinima }}" 
                                   wire:model="actividades.{{ $index }}.fecha_limite" 
                                   class="form-control form-control-solid bg-white border border-gray-300 rounded-3 fs-8" />
                        </div>

                        <div class="col-lg-2 text-end">
                            <button type="button" 
                                    wire:click="guardarActividad({{ $index }})" 
                                    class="btn btn-sm btn-primary w-100 fw-bold fs-8 rounded-3">
                                <i class="bi bi-floppy me-1"></i> {{ isset($actividad['id']) ? 'Actualizar' : 'Guardar' }}
                            </button>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>