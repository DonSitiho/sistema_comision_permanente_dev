<div>
    @if ($conversacion)
        <div class="mb-3" style="max-height:400px; overflow-y:auto">
            @foreach ($conversacion->mensajes as $msg)
                <div class="mb-2 {{ $msg->emisor_id === auth()->id() ? 'text-end' : '' }}">
                    <div class="small text-muted">{{ $msg->emisor->name }}</div>
                    <div class="d-inline-block p-2 rounded bg-light">{{ $msg->contenido }}</div>
                </div>
            @endforeach
        </div>

        <form wire:submit="enviar" class="d-flex gap-2">
            <input wire:model="nuevoMensaje" type="text" class="form-control" placeholder="Escribe un mensaje...">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        @error('nuevoMensaje')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    @else
        <div class="text-center text-muted p-5">Selecciona una conversación.</div>
    @endif
</div>