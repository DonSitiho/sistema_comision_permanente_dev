<?php

namespace App\Livewire\Acuerdos;

use App\Models\Compromiso;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SeguimientoIndex extends Component
{
    use WithPagination;

    public string $filtroEstado = "";
    public bool $soloMisCompromisos = true;

    public function updatingFiltroEstado()
    {
        $this->resetPage();
    }

    public function marcarCumplido(int $id): void
    {
        $compromiso = Compromiso::findOrFail($id);

        if ($compromiso->responsable_id !== Auth::id()
            && !Auth::user()->hasRole("Secretario Tecnico")) {
            $this->dispatch("error", "Solo el responsable puede marcar este compromiso.");
            return;
        }

        $compromiso->marcarCumplido();
        $this->dispatch("success", "Compromiso marcado como cumplido.");
    }

    public function render()
    {
        $query = Compromiso::with(["acuerdo", "responsable"])
            ->when($this->soloMisCompromisos, function ($q) {
                $q->deResponsable(Auth::id());
            })
            ->when($this->filtroEstado, function ($q, $estado) {
                $q->where("estado", $estado);
            })
            ->orderBy("fecha_limite");

        return view("livewire.acuerdos.seguimiento-index", [
            "compromisos" => $query->paginate(15),
        ]);
    }
}