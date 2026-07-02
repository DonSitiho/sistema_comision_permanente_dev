<?php

namespace App\Livewire\Acuerdos;

use App\Models\Acuerdo;
use App\Models\Sesion;
use App\Services\AcuerdoFolioService;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AcuerdoModal extends Component
{
    public int $sesion_id;
    public string $descripcion = "";

    protected function rules(): array
    {
        return ["descripcion" => "required|string|min:10"];
    }

    public function mount(int $sesionId): void
    {
        $sesion = Sesion::findOrFail($sesionId);

        // Solo se registran acuerdos de sesiones ya realizadas
        if ($sesion->estado !== "realizada") {
            abort(422, "Solo se pueden registrar acuerdos de sesiones realizadas.");
        }

        $this->sesion_id = $sesionId;
    }

    public function submit(): void
    {
        Gate::authorize("create", Acuerdo::class);
        $this->validate();

        $acuerdo = Acuerdo::create([
            "sesion_id" => $this->sesion_id,
            "folio" => AcuerdoFolioService::generar(),
            "descripcion" => $this->descripcion,
            "estado" => "registrado",
        ]);

        $this->dispatch("success", "Acuerdo {$acuerdo->folio} registrado.");
        $this->dispatch("acuerdo-creado", id: $acuerdo->id);
        $this->reset("descripcion");
    }

    public function render()
    {
        return view("livewire.acuerdos.acuerdo-modal");
    }
}