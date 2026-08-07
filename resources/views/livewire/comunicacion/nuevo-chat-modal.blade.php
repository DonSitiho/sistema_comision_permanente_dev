<div>
    <div class="mb-4">
        <label class="form-label">Buscar usuario</label>
        <input wire:model.live.debounce.300ms="busqueda" type="text" class="form-control" placeholder="Nombre...">
    </div>
    <div style="max-height:250px; overflow-y:auto">
        @forelse ($usuarios as $usuario)
            <div wire:click="iniciar({{ $usuario->id }})"
                 class="d-flex align-items-center p-2 rounded cursor-pointer bg-hover-light">
                <span class="fw-bold">{{ $usuario->name }}</span>
            </div>
        @empty
            @if (trim($busqueda) !== "")
                <div class="text-center text-muted p-4">Sin resultados.</div>
            @endif
        @endforelse
    </div>
</div>