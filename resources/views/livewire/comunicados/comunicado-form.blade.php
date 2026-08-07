<div class="row g-5">
    <div class="col-xl-8">
        <div class="card card-flush shadow-sm p-6">
            <div class="card-header px-0 pt-0">
                <h2 class="card-title fw-bold">{{ $comunicadoId ? 'Editar comunicado' : 'Crear comunicado' }}</h2>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="mb-6">
                    <label class="form-label fw-semibold required">Asunto</label>
                    <input type="text" wire:model="titulo" class="form-control" placeholder="Ingresa un asunto descriptivo...">
                    @error('titulo') <div class="text-danger fs-8 mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-6">
                    <label class="form-label fw-semibold required">Categoria</label>
                    <select wire:model="categoria_id" class="form-select">
                        <option value="">Selecciona una categoria...</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                        @endforeach
                    </select>
                    @error('categoria_id') <div class="text-danger fs-8 mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="mb-6">
                    <label class="form-label fw-semibold required">Cuerpo del mensaje</label>
                    <textarea wire:model="cuerpo" rows="10" class="form-control" placeholder="Escribe el contenido del comunicado..."></textarea>
                    @error('cuerpo') <div class="text-danger fs-8 mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox" wire:model="obligatorio" class="form-check-input" id="obligatorioSwitch">
                    <label class="form-check-label fw-semibold" for="obligatorioSwitch">
                        Comunicado obligatorio (requiere "Acepto" de cada destinatario)
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card card-flush shadow-sm p-6 mb-5">
            <div class="card-header px-0 pt-0">
                <h3 class="card-title fw-bold">Destinatarios</h3>
            </div>
            <div class="card-body px-0 pt-0">
                <div class="d-flex flex-column gap-3">
                    <label class="d-flex align-items-start border rounded p-3 {{ $alcance === 'general' ? 'border-primary bg-light-primary' : '' }}">
                        <input type="radio" wire:model.live="alcance" value="general" class="form-check-input me-3 mt-1">
                        <span>
                            <span class="fw-bold d-block">Todos los usuarios</span>
                            <span class="text-muted fs-7">Se envia a toda la organizacion</span>
                        </span>
                    </label>
                    <label class="d-flex align-items-start border rounded p-3 {{ $alcance === 'region' ? 'border-primary bg-light-primary' : '' }}">
                        <input type="radio" wire:model.live="alcance" value="region" class="form-check-input me-3 mt-1">
                        <span class="w-100">
                            <span class="fw-bold d-block mb-2">Por región</span>
                            @if ($alcance === 'region')
                                <div class="d-flex flex-wrap gap-2">
                                    @forelse ($regiones as $region)
                                        <span wire:click.stop="toggleRegion({{ $region->id }})"
                                              class="badge {{ in_array($region->id, $regionesSeleccionadas) ? 'badge-primary' : 'badge-light' }} cursor-pointer">
                                            {{ $region->nombre }}
                                        </span>
                                    @empty
                                        <span class="text-muted fs-8">No hay regiones configuradas.</span>
                                    @endforelse
                                </div>
                            @endif
                        </span>
                    </label>
                    <label class="d-flex align-items-start border rounded p-3 {{ $alcance === 'lista' ? 'border-primary bg-light-primary' : '' }}">
                        <input type="radio" wire:model.live="alcance" value="lista" class="form-check-input me-3 mt-1">
                        <span class="w-100">
                            <span class="fw-bold d-block mb-2">Lista de usuarios</span>
                            @if ($alcance === 'lista')
                                <input type="text" wire:model.live.debounce.300ms="busquedaUsuario" class="form-control form-control-sm mb-2" placeholder="Buscar usuarios...">
                                @if ($usuariosEncontrados->isNotEmpty())
                                    <div class="d-flex flex-column gap-1 mb-2" style="max-height:140px; overflow-y:auto;">
                                        @foreach ($usuariosEncontrados as $usuario)
                                            <div wire:click.stop="agregarUsuario({{ $usuario->id }})" class="d-flex align-items-center justify-content-between bg-hover-light rounded p-2 cursor-pointer">
                                                <span class="fs-8">{{ $usuario->name }}</span>
                                                <i class="ki-duotone ki-plus fs-6 text-primary"></i>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach ($usuariosSeleccionados as $id)
                                        <span class="badge badge-light-primary d-flex align-items-center gap-1">
                                            {{ $usuariosSeleccionadosInfo[$id]->name ?? '#'.$id }}
                                            <i class="ki-duotone ki-cross fs-7 cursor-pointer" wire:click.stop="quitarUsuario({{ $id }})"></i>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </span>
                    </label>
                </div>
                @error('regionesSeleccionadas') <div class="text-danger fs-8 mt-2">{{ $message }}</div> @enderror
                @error('usuariosSeleccionados') <div class="text-danger fs-8 mt-2">{{ $message }}</div> @enderror
            </div>
        </div>
        <button type="button" wire:click="enviar" class="btn btn-primary w-100 mb-3">Enviar ahora</button>
        <button type="button" wire:click="guardarBorrador" class="btn btn-light w-100">Guardar borrador</button>
    </div>
</div>