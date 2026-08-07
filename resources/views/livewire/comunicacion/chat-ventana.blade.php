<div>
    @if ($conversacion)
        <div class="mb-3" style="max-height:400px; overflow-y:auto">
            @foreach ($conversacion->mensajes as $msg)
                <div class="mb-2 {{ $msg->emisor_id === auth()->id() ? 'text-end' : '' }}">
                    <div class="small text-muted">{{ $msg->emisor->name }}</div>
                    <div class="d-inline-block p-2 rounded bg-light">
                        {{ $msg->contenido }}
                        @foreach ($msg->documentos as $doc)
                            <div class="small mt-1">
                                <i class="ki-duotone ki-paper-clip fs-6"></i>
                                {{ $doc->nombre_original }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <form wire:submit="enviar" class="d-flex gap-2 align-items-end">
            <input wire:model="nuevoMensaje" type="text" class="form-control" placeholder="Escribe un mensaje...">
            <label class="btn btn-light-primary mb-0" title="Adjuntar archivo">
                <i class="ki-duotone ki-paper-clip fs-3"></i>
                <input type="file" wire:model="archivoAdjunto" class="d-none">
            </label>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        @if ($archivoAdjunto)
            <div class="small text-muted mt-1">Adjunto listo: {{ $archivoAdjunto->getClientOriginalName() }}</div>
        @endif
        @error('nuevoMensaje')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
        @error('archivoAdjunto')
            <span class="text-danger small">{{ $message }}</span>
        @enderror
    @else
        <div class="text-center text-muted p-5">Selecciona una conversación.</div>
    @endif
</div>