<?php

namespace App\Livewire\Acuerdos;

use App\Models\Acuerdo;
use App\Models\Compromiso;
use App\Models\User;
use App\Services\NotificacionService;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class CompromisoModal extends Component
{
    public int $acuerdo_id;
    public string $descripcion = "";
    public ?int $responsable_id = null;
    public string $fecha_limite = "";

    protected function rules(): array
    {
        return [
            "descripcion" => "required|string|min:10",
            "responsable_id" => "required|exists:users,id",
            "fecha_limite" => "nullable|date|after_or_equal:today",
        ];
    }

    public function submit(): void
    {
        $acuerdo = Acuerdo::findOrFail($this->acuerdo_id);
        Gate::authorize("update", $acuerdo);
        $this->validate();

        Compromiso::create([
            "acuerdo_id" => $this->acuerdo_id,
            "responsable_id" => $this->responsable_id,
            "descripcion" => $this->descripcion,
            "fecha_limite" => $this->fecha_limite ?: null,
            "estado" => "pendiente",
        ]);

        NotificacionService::enviar(
            destinatario: User::find($this->responsable_id),
            tipo: "compromiso",
            titulo: "Nuevo compromiso asignado",
            mensaje: $this->descripcion,
        );

        $this->dispatch("success", "Compromiso registrado y notificado.");
        $this->reset(["descripcion", "responsable_id", "fecha_limite"]);
    }

    public function render()
    {
        return view("livewire.acuerdos.compromiso-modal", [
            "usuarios" => User::orderBy("name")->get(),
        ]);
    }
}