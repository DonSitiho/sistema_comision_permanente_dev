{{-- <div class="p-5">
    <div class="mb-4">
        <label class="form-label fw-bold">Título</label> 
        <input wire:model="titulo" type="text" class="form-control" placeholder="Título de la convocatoria"> 
        @error('titulo') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Descripción</label> 
        <textarea wire:model="descripcion" class="form-control" placeholder="Descripción..."></textarea>
        @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Fecha de la sesión</label> 
        <input wire:model="fecha_sesion" type="datetime-local" class="form-control"> 
        @error('fecha_sesion') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Lugar</label> 
        <input wire:model="lugar" type="text" class="form-control" placeholder="Lugar de la sesión"> 
        @error('lugar') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="text-end pt-4 mt-5">
        <button type="button" wire:click="submit" class="btn btn-primary">
            Guardar Convocatoria
        </button>
    </div>
</div> --}}
{{-- resources/views/livewire/sesiones/convocatoria-modal.blade.php --}}
<div class="p-5">
    <!-- Usamos $this->convocatoria_id para garantizar la existencia de la variable -->
    @if($this->convocatoria_id)
        <div class="alert alert-light-warning d-flex align-items-center p-0 mb-3">
           
        </div>
    @endif

    <div class="mb-4">
        <label class="form-label fw-bold">Título</label> 
        <input wire:model="titulo" type="text" class="form-control" placeholder="Título de la convocatoria" {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}> 
        @error('titulo') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Descripción</label> 
        <textarea wire:model="descripcion" class="form-control" placeholder="Descripción..." {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}></textarea>
        @error('descripcion') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Fecha y Hora de la sesión</label> 
        <input wire:model="fecha_sesion" type="datetime-local" class="form-control border-warning"> 
        @error('fecha_sesion') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Lugar</label> 
        <input wire:model="lugar" type="text" class="form-control" placeholder="Lugar de la sesión" {{ $this->convocatoria_id ? 'readonly bg-light text-muted' : '' }}> 
        @error('lugar') <span class="text-danger small">{{ $message }}</span> @enderror
    </div>

    <div class="text-end pt-4 mt-5">
        @if($this->convocatoria_id)
            <button id="sin_fecha" type="button" wire:click="posponerConvocatoria(true)" class="btn btn-info">
                Posponer sin especificar la nueva fecha
            </button>
            <button type="button" wire:click="posponerConvocatoria(false)" class="btn btn-warning">
                Confirmar
            </button>
        @else
            <button type="button" wire:click="submit" class="btn btn-primary">
                Guardar Convocatoria
            </button>
        @endif
    </div>
</div>