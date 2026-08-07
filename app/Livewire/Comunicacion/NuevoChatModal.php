<?php
namespace App\Livewire\Comunicacion;
use App\Models\User;
use App\Services\MensajeService;
use Livewire\Component;
class NuevoChatModal extends Component
{
    public string $busqueda = "";
    public function usuarios()
    {
        if (trim($this->busqueda) === "") {
            return collect();
        }
        return User::where("id", "!=", auth()->id())
            ->where("name", "like", "%{$this->busqueda}%")
            ->orderBy("name")
            ->limit(10)
            ->get();
    }
    public function iniciar(int $usuarioId): void
    {
        $otro = User::findOrFail($usuarioId);
        $conversacion = MensajeService::iniciarIndividual(auth()->user(), $otro);
        $this->busqueda = "";
        $this->dispatch("conversacion-seleccionada", id: $conversacion->id);
    }
    public function render()
    {
        return view("livewire.comunicacion.nuevo-chat-modal", [
            "usuarios" => $this->usuarios(),
        ]);
    }
}