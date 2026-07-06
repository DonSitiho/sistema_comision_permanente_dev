<?php

namespace App\Livewire\Comunicacion;

use App\Models\Conversacion;
use Livewire\Attributes\On;
use Livewire\Component;

class ConversacionIndex extends Component
{
    public ?int $conversacionActivaId = null;

    // Se ejecuta cuando ChatVentana avisa que llego un mensaje nuevo,
    // para refrescar el orden y el contador de no leidos de la lista.
    #[On("mensaje-enviado")]
    public function refrescar(): void {}

    public function seleccionar(int $conversacionId): void
    {
        $this->conversacionActivaId = $conversacionId;
        $this->dispatch("conversacion-seleccionada", id: $conversacionId);
    }

    public function render()
    {
        $conversaciones = Conversacion::query()
            ->whereHas("participantes", function ($q) {
                $q->where("user_id", auth()->id());
            })
            ->with(["participantes.user", "mensajes" => function ($q) {
                $q->latest()->limit(1); // ultimo mensaje para la vista previa
            }])
            ->orderByDesc(
                fn ($q) => $q->select("created_at")
                    ->from("f1com_mensajes")
                    ->whereColumn("conversacion_id", "f1com_conversaciones.id")
                    ->latest()->limit(1)
            )
            ->get();

        return view("livewire.comunicacion.conversacion-index", [
            "conversaciones" => $conversaciones,
        ]);
    }
}