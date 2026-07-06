<div>
    <form wire:submit="crear">
        <div class="mb-4">
            <label class="form-label">Nombre del grupo</label>
            <input wire:model="nombre" type="text" class="form-control">
            @error('nombre')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label class="form-label">Participantes</label>
            <div style="max-height:200px; overflow-y:auto">
                @foreach ($usuarios as $usuario)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            wire:model="participantesSeleccionados" value="{{ $usuario->id }}"
                            id="user-{{ $usuario->id }}">
                        <label class="form-check-label" for="user-{{ $usuario->id }}">
                            {{ $usuario->name }}
                        </label>
                    </div>
                @endforeach
            </div>
            @error('participantesSeleccionados')
                <span class="text-danger small">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Crear grupo</button>
    </form>
</div>