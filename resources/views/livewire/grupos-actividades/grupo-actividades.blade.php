<div class="w-100 p-4 bg-light-subtle">

    <div class="d-flex align-items-center justify-content-between mb-6">
        <div>
            <h1 class="text-gray-900 fw-bolder fs-2m mb-0">Crear Grupo de Actividades</h1>
        </div>
    </div>

    <div class="card card-flush shadow-xs border border-gray-200 rounded-4 mb-6 bg-white">
        <div class="card-header border-0 pt-5 pb-0">
            <h3 class="card-title fw-bolder text-gray-900 fs-4">Información del Grupo</h3>
        </div>
        <div class="card-body pt-4">
            <div class="mb-4">
                <label class="form-label fw-bold text-gray-800 fs-7">
                    Nombre del Grupo
                </label>
                <input type="text" 
                       wire:model="nombre" 
                       @if($grupoGuardado) disabled @endif
                       class="form-control form-control-solid bg-light-subtle border border-gray-300 rounded-3 fs-7 py-3" 
                       placeholder="Ej. Preparación de Estados Financieros" />
                @error('nombre') <span class="text-danger fs-8 mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            <div class="mb-2">
                <label class="form-label fw-bold text-gray-800 fs-7">
                    Descripción General <span class="text-gray-400 fw-normal">(Opcional)</span>
                </label>
                <textarea wire:model="descripcion" 
                          @if($grupoGuardado) disabled @endif
                          rows="3" 
                          class="form-control form-control-solid bg-light-subtle border border-gray-300 rounded-3 fs-7" 
                          placeholder="Describe el propósito general de este grupo de actividades"></textarea>
                @error('descripcion') <span class="text-danger fs-8 mt-1 d-block">{{ $message }}</span> @enderror
            </div>

            @if(!$grupoGuardado)
                <div class="d-flex align-items-center justify-content-end gap-3 mt-6">
                    <button type="button" wire:click="cancelar" class="btn btn-sm btn-light fw-bold px-5 py-3 rounded-3 border border-gray-300">
                        Cancelar
                    </button>
                    <button type="button" wire:click="crearGrupo" class="btn btn-sm btn-primary fw-bold px-5 py-3 rounded-3">
                        <i class="bi bi-floppy me-2"></i> Guardar Grupo
                    </button>
                </div>
            @endif
        </div>
    </div>

    @if($grupoGuardado)
        <div class="d-flex align-items-center justify-content-end gap-3 mt-4">
            <button type="button" wire:click="cancelar" class="btn btn-sm btn-primary fw-bold px-5 py-3 rounded-3">
                <i class="bi bi-check-circle me-2"></i> Agregar Actividades Después
            </button>
        </div>
        @livewire('grupos-actividades.actividades', [
            'grupo_id' => $grupo_id, 
            'convocatoria_id' => $convocatoria_id
        ], key('actividades-grupo-' . $grupo_id))

    @endif

</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('swal:alert', (event) => {
            // Extraer parámetros enviados desde PHP
            const data = Array.isArray(event) ? event[0] : event;

            Swal.fire({
                icon: data.icon || 'info',
                title: data.title || '',
                text: data.text || '',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
        });
    });
</script>