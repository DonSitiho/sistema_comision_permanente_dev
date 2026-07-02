<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" wire:model.live="soloMisCompromisos" id="soloMios">
            <label class="form-check-label" for="soloMios">Solo mis compromisos</label>
        </div>

        <select wire:model.live="filtroEstado" class="form-select" style="width:auto">
            <option value="">Todos los estados</option>
            <option value="pendiente">Pendiente</option>
            <option value="en_proceso">En proceso</option>
            <option value="cumplido">Cumplido</option>
        </select>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Acuerdo</th>
                <th>Responsable</th>
                <th>Fecha límite</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($compromisos as $compromiso)
                <tr>
                    <td>{{ $compromiso->descripcion }}</td>
                    <td>{{ $compromiso->acuerdo->folio }}</td>
                    <td>{{ $compromiso->responsable->name }}</td>
                    <td>{{ $compromiso->fecha_limite?->format('d/m/Y') ?? 'Sin fecha' }}</td>
                    <td><span class="badge bg-secondary">{{ $compromiso->estado }}</span></td>
                    <td>
                        @if ($compromiso->estado !== 'cumplido')
                            <button wire:click="marcarCumplido({{ $compromiso->id }})"
                                class="btn btn-sm btn-success">
                                Marcar cumplido
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">Sin compromisos.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $compromisos->links() }}
</div>