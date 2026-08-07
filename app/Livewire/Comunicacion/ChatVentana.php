<?php
namespace App\Livewire\Comunicacion;
use App\Models\Conversacion;
use App\Services\MensajeService;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
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
        $this->conversacion->participantes()
            ->where("user_id", auth()->id())
            ->update(["ultimo_leido_at" => now()]);
    }
    public function enviar(): void
    {
        $this->validate([
            "nuevoMensaje" => "required_without:archivoAdjunto|nullable|string|max:4000",
            "archivoAdjunto" => "nullable|file|max:10240",
        ]);
        MensajeService::enviar(
            conversacion: $this->conversacion,
            emisor: auth()->user(),
            contenido: $this->nuevoMensaje ?: "",
            adjunto: $this->archivoAdjunto,
        );
        $this->reset(["nuevoMensaje", "archivoAdjunto"]);
        $this->conversacion->refresh()->load("mensajes.emisor");
        $this->dispatch("mensaje-enviado");
    }
    public function render()
    {
        return view("livewire.comunicacion.chat-ventana");
    }
}