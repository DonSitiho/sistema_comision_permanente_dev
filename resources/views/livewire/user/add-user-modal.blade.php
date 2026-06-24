{{-- resources/views/livewire/user/add-user-modal.blade.php --}}
<div>
    <div class="modal fade" id="kt_modal_add_user" tabindex="-1" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
 
                <div class="modal-header">
                    <h2 class="fw-bold">
                        {{ $edit_mode ? "Editar Usuario" : "Nuevo Usuario" }}
                    </h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
 
                <div class="modal-body px-5 py-5">
                    <form wire:submit="submit">
 
                        {{-- Avatar --}}
                        <div class="d-flex flex-column align-items-center mb-7">
                            @if ($saved_avatar && !$avatar)
                                <img src="{{ $saved_avatar }}" class="rounded-circle mb-3"
                                     style="width:80px;height:80px;object-fit:cover;">
                            @elseif ($avatar)
                                <img src="{{ $avatar->temporaryUrl() }}" class="rounded-circle mb-3"
                                     style="width:80px;height:80px;object-fit:cover;">
                            @endif
                            <input type="file" wire:model="avatar" class="form-control form-control-sm w-auto">
                            @error("avatar")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        {{-- Nombre --}}
                        <div class="mb-5">
                            <label class="form-label required">Nombre completo</label>
                            <input wire:model="name" type="text" class="form-control"
                                   placeholder="Nombre del servidor público">
                            @error("name")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        {{-- Email --}}
                        <div class="mb-5">
                            <label class="form-label required">Correo institucional</label>
                            <input wire:model="email" type="email" class="form-control"
                                   placeholder="usuario@dependencia.gob.mx">
                            @error("email")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        {{-- Cargo --}}
                        <div class="mb-5">
                            <label class="form-label">Cargo</label>
                            <input wire:model="cargo" type="text" class="form-control"
                                   placeholder="Contralor Municipal, Enlace, etc.">
                            @error("cargo")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        {{-- Dependencia --}}
                        <div class="mb-5">
                            <label class="form-label">Dependencia</label>
                            <select wire:model="dependencia_id" class="form-select">
                                <option value="">— Sin dependencia asignada —</option>
                                @foreach ($dependencias as $dep)
                                    <option value="{{ $dep->id }}">
                                        {{ $dep->nombre }}
                                        @if($dep->tipo !== "estatal") ({{ $dep->tipo_label }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            @error("dependencia_id")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        {{-- Rol --}}
                        <div class="mb-5">
                            <label class="form-label required">Rol del sistema</label>
                            <select wire:model="role" class="form-select">
                                <option value="">— Seleccionar rol —</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r->name }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            @error("role")<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
 
                        @if (!$edit_mode)
                        <div class="alert alert-info d-flex align-items-center p-3 mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            <span class="small">
                                El usuario recibirá un correo para establecer su contraseña.
                            </span>
                        </div>
                        @endif
 
                        <div class="d-flex justify-content-end gap-3 pt-3">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <span wire:loading.remove>
                                    {{ $edit_mode ? "Guardar cambios" : "Crear usuario" }}
                                </span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-1"></span>
                                    Procesando...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>