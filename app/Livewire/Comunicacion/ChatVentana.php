<?php

namespace App\Livewire\Comunicacion;

use App\Models\Conversacion;
use App\Services\MensajeService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
// use App\Services\CifradoService; // del modulo de Documentos, Dev 1 -- TODO: descomentar cuando este listo

class ChatVentana extends Component
{
    use WithFileUploads;

    public ?Conversacion $conversacion = null;
    public string $nuevoMensaje = "";
    public $archivoAdjunto = null;

    #[On("conversacion-seleccionada")]
    public function abrir(int $id): void
    {
        $this->conversacion = Conversacion::with([
            "mensajes.emisor", "participantes.user",
        ])->findOrFail($id);

        // Marcar como leido al abrir: actualiza ultimo_leido_at del participante
        $this->conversacion->participantes()
            ->where("user_id", auth()->id())
            ->update(["ultimo_leido_at" => now()]);
    }

    public function enviar(): void
    {
        $this->validate(["nuevoMensaje" => "required|string|max:4000"]);

        MensajeService::enviar(
            conversacion: $this->conversacion,
            emisor: auth()->user(),
            contenido: $this->nuevoMensaje,
        );

        $this->nuevoMensaje = "";
        $this->conversacion->refresh()->load("mensajes.emisor");
        $this->dispatch("mensaje-enviado"); // avisa a ConversacionIndex
    }

    // TODO: depende de HasDocumentos en Mensaje y de CifradoService::cifrarYAlmacenar() de Dev 1.
    // Nombre real del metodo confirmado en su guia: cifrarYAlmacenar(), NO guardar() como decia la guia de Dev2.
    // Por ahora envia el mensaje de texto normal; el bloque de adjunto queda comentado.
    public function enviarConAdjunto(): void
    {
        $this->validate([
            "nuevoMensaje" => "nullable|string|max:4000",
            "archivoAdjunto" => "nullable|file|max:10240", // 10MB
        ]);

        $mensaje = MensajeService::enviar(
            conversacion: $this->conversacion,
            emisor: auth()->user(),
            contenido: $this->nuevoMensaje ?: "",
        );

        // if ($this->archivoAdjunto) {
        //     $contenido = file_get_contents($this->archivoAdjunto->getRealPath());
        //     $rutaDestino = "mensaje_" . $mensaje->id . "/" . uniqid() . ".enc";
        //     $rutaFinal = app(CifradoService::class)->cifrarYAlmacenar($contenido, $rutaDestino);
        //
        //     $mensaje->documentos()->create([
        //         "nombre_original" => $this->archivoAdjunto->getClientOriginalName(),
        //         "ruta_cifrada" => $rutaFinal,
        //         "mime_type" => $this->archivoAdjunto->getMimeType(),
        //         "tamano" => $this->archivoAdjunto->getSize(),
        //         "subido_por" => auth()->id(),
        //         "categoria" => "adjunto_chat",
        //     ]);
        // }

        $this->reset(["nuevoMensaje", "archivoAdjunto"]);
        $this->conversacion->refresh()->load("mensajes.emisor");
        $this->dispatch("mensaje-enviado");
    }

    public function render()
    {
        return view("livewire.comunicacion.chat-ventana");
    }
}