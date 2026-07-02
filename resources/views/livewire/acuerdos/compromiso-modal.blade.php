<div>
    <form wire:submit="submit">
        <div class="mb-4">
            <label class="form-label">Descripción del compromiso</label>
            <textarea wire:model="descripcion" class="form-control" rows="3"></textarea>
            @error('descripcion')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Responsable</label>
            <select wire:model="responsable_id" class="form-select">
                <option value="">Seleccionar...</option>
                @foreach ($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endforeach
            </select>
            @error('responsable_id')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Fecha límite (opcional)</label>
            <input type="date" wire:model="fecha_limite" class="form-control">
            @error('fecha_limite')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            Registrar compromiso
        </button>
    </form>
</div>