<div class="card card-flush shadow-sm p-6">
    <div class="card-header px-0 pt-0">
        <h3 class="card-title fw-bold">Comunicados enviados</h3>
        <div class="card-toolbar"><span class="badge badge-light fs-7">Total: {{ $comunicados->total() }}</span></div>
    </div>
    <div class="card-body px-0 pt-0">
        <div class="row g-3 mb-5">
            <div class="col-md-5"><input type="text" wire:model.live.debounce.300ms="busqueda" class="form-control form-control-sm" placeholder="Titulo o palabra clave"></div>
            <div class="col-md-3">
                <select wire:model.live="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="borrador">Borrador</option>
                    <option value="enviado">Enviado</option>
                    <option value="archivado">Archivado</option>
                </select>
            </div>
            <div class="col-md-3"><input type="date" wire:model.live="fecha" class="form-control form-control-sm"></div>
            <div class="col-md-1"><button type="button" wire:click="limpiar" class="btn btn-sm btn-light w-100">Limpiar</button></div>
        </div>
        <div class="table-responsive">
            <table class="table table-row-dashed table-row-gray-300 align-middle gy-3">
                <thead>
                    <tr class="fw-bold text-muted text-uppercase fs-8">
                        <th>Titulo</th><th>Categoria</th><th>Fecha</th><th>Estado</th><th>Destinatarios</th><th>Lectura</th><th>Acciones</th>
                </thead>
                <tbody>
                    @forelse ($comunicados as $comunicado)
                        <tr wire:key="env-{{ $comunicado->id }}" wire:click="seleccionar({{ $comunicado->id }})"
                            class="cursor-pointer {{ $seleccionadoId === $comunicado->id ? 'bg-light-primary' : '' }}">
                            <td>
                                <span class="fw-bold d-block">{{ $comunicado->titulo }}</span>
                                <span class="text-muted fs-8">ID: CMU-{{ str_pad($comunicado->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td><span class="badge badge-light-{{ $comunicado->categoria->color ?? 'secondary' }}">{{ $comunicado->categoria->nombre }}</span></td>
                            <td>{{ $comunicado->enviado_at?->format('d M Y') ?? '--' }}</td>
                            <td>
                                <span class="badge {{ match($comunicado->estado) { 'enviado' => 'badge-light-success', 'borrador' => 'badge-light-secondary', default => 'badge-light-warning' } }}">
                                    {{ ucfirst($comunicado->estado) }}
                                </span>
                            </td>
                            <td>{{ $comunicado->destinatarios()->count() }}</td>
                           <td>
                                @if ($comunicado->estado === 'enviado')
                                    <span class="fs-8 text-muted">{{ $comunicado->porcentajeLectura() }}%</span>
                                @else
                                    <span class="text-muted fs-8">--</span>
                                @endif
                            </td>
                            <td>
                                @if ($comunicado->estado === 'borrador')
                                    <a href="{{ route('comunicados.editar', $comunicado->id) }}"
                                       onclick="event.stopPropagation()"
                                       class="btn btn-sm btn-light-primary">Editar</a>
                                @else
                                    <span class="text-muted fs-8">--</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-8">No hay comunicados que coincidan con el filtro.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $comunicados->links() }}</div>
    </div>
</div>