{{-- resources/views/livewire/bitacora/bitacora-index.blade.php --}}
<div>
    {{-- Filtros --}}
    <div class="card mb-5">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <select wire:model.live="filtroAccion" class="form-select form-select-sm">
                        <option value="">Todas las acciones</option>
                        @foreach ($acciones as $accion)
                            <option value="{{ $accion }}">{{ $accion }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="filtroUsuario" class="form-select form-select-sm">
                        <option value="">Todos los usuarios</option>
                        @foreach ($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input wire:model.live="filtroDesde" type="date"
                           class="form-control form-control-sm" placeholder="Desde">
                </div>
                <div class="col-md-3">
                    <input wire:model.live="filtroHasta" type="date"
                           class="form-control form-control-sm" placeholder="Hasta">
                </div>
            </div>
        </div>
    </div>
 
    {{-- Tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha / Hora</th>
                            <th>Usuario</th>
                            <th>Acción</th>
                            <th>Entidad</th>
                            <th>ID</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td class="text-nowrap">
                                <small>{{ $log->created_at->format("d/m/Y H:i:s") }}</small>
                            </td>
                            <td>{{ $log->user?->name ?? "Sistema" }}</td>
                            <td>
                                <span class="badge
                                    @if(str_contains($log->accion, "login")) bg-info
                                    @elseif(str_contains($log->accion, "failed")) bg-danger
                                    @elseif($log->accion === "created") bg-success
                                    @elseif($log->accion === "deleted") bg-warning
                                    @else bg-secondary @endif">
                                    {{ $log->accion }}
                                </span>
                            </td>
                            <td>{{ $log->entidad ?? "—" }}</td>
                            <td>{{ $log->entidad_id ?? "—" }}</td>
                            <td><small class="text-muted">{{ $log->ip }}</small></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Sin registros para los filtros seleccionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $logs->links() }}
        </div>
    </div>
</div>
