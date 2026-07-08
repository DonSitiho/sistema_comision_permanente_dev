{{-- resources/views/livewire/notificaciones/historial-notificaciones.blade.php --}}
<div class="card shadow-sm">
    <div class="card-header border-0 pt-6">
        <div class="card-title">
            <h3 class="fw-bold text-gray-900">Historial de Notificaciones</h3>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="d-flex flex-column gap-4 mt-5">
            @forelse($historial as $item)
                <div wire:key="hist-item-{{ $item->id }}" 
                     wire:click="leerNotificacion({{ $item->id }})"
                     style="cursor: pointer;"
                     class="p-5 rounded d-flex flex-column border transition-3s {{ $item->leida_at ? 'bg-white border-gray-200' : 'bg-light-primary border-primary-clarity shadow-sm' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5 {{ $item->leida_at ? 'text-gray-700' : 'text-gray-900' }}">
                            {{ $item->titulo }}
                        </span>
                        <span class="text-muted fs-7">{{ $item->created_at->format('d/m/Y') }}</span>
                    </div>
                    <p class="text-gray-600 fs-6 mt-2 mb-0">{{ $item->mensaje }}</p>
                </div>
            @empty
                <div class="text-center text-muted p-10">
                    No tienes registros en tu historial de notificaciones.
                </div>
            @endforelse
        </div>
    </div>
</div>