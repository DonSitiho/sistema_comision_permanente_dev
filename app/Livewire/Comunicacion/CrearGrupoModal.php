<?php

namespace App\Livewire\Comunicacion;

use App\Models\Sesion;
use App\Models\User;
use App\Services\MensajeService;
use Livewire\Component;

class CrearGrupoModal extends Component
{
    public string $nombre = "";
    public array $participantesSeleccionados = [];
    public ?int $sesionOrigenId = null;

    protected $rules = [
        "nombre" => "required|string|max:150",
        "participantesSeleccionados" => "required|array|min:1",
    ];

    // Si el modal se abre desde una sesion, precarga el nombre
    // y los asistentes como participantes sugeridos.
    public function abrirDesdeSesion(int $sesionId): void
    {
        $sesion = Sesion::with("asistentes")->findOrFail($sesionId);

        $this->sesionOrigenId = $sesion->id;
        $this->nombre = "Sesion: " . ($sesion->convocatoria?->titulo ?? "Sin titulo");
        $this->participantesSeleccionados = $sesion->asistentes
            ->pluck("user_id")
            ->reject(fn ($id) => $id === auth()->id()) // el creador se agrega aparte
            ->values()->toArray();
    }

    public function crear(): void
    {
        $this->validate();

        $conversacion = MensajeService::crearGrupo(
            nombre: $this->nombre,
            creador: auth()->user(),
            participanteIds: $this->participantesSeleccionados,
            sesionId: $this->sesionOrigenId,
        );

        $this->dispatch("conversacion-seleccionada", id: $conversacion->id);
        $this->reset(["nombre", "participantesSeleccionados", "sesionOrigenId"]);
    }

    public function render()
    {
        return view("livewire.comunicacion.crear-grupo-modal", [
            "usuarios" => User::where("id", "!=", auth()->id())
                ->orderBy("name")->get(),
        ]);
    }
}