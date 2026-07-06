<div>
    <div class="list-group">
        @forelse ($conversaciones as $conv)
            <button wire:click="seleccionar({{ $conv->id }})"
                class="list-group-item list-group-item-action {{ $conversacionActivaId === $conv->id ? 'active' : '' }}">
                <div class="fw-bold">{{ $conv->nombreParaUsuario(auth()->id()) }}</div>
                <div class="text-muted small">
                    {{ $conv->mensajes->first()->contenido ?? 'Sin mensajes' }}
                </div>
            </button>
        @empty
            <div class="text-center text-muted p-4">No tienes conversaciones.</div>
        @endforelse
    </div>
</div>