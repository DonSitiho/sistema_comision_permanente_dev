<div class="p-5">
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
</div>