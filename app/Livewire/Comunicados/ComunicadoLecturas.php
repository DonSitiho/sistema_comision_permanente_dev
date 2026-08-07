<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use App\Services\ComunicadoNotificador;
use Livewire\Attributes\On;
use Livewire\Component;
class ComunicadoLecturas extends Component
{
    public ?Comunicado $comunicado = null;
    public string $pestana = "leidos"; // leidos | pendientes

    #[On("ver-lecturas")]
    public function abrir(int $id): void
    {
        $this->comunicado = Comunicado::with("categoria")->findOrFail($id);
        $this->authorize("verLecturas", $this->comunicado);
        $this->pestana = "leidos";
    }

    public function cerrar(): void
    {
        $this->comunicado = null;
    }

    public function cambiarPestana(string $pestana): void
    {
        $this->pestana = $pestana;
    }

    public function enviarRecordatorio(ComunicadoNotificador $notificador): void
    {
        if (!$this->comunicado) {
            return;
        }
        $notificador->recordarPendientes($this->comunicado);
        $this->dispatch("recordatorio-enviado");
    }

    public function render()
    {
        $leidos = $this->comunicado?->destinatarios()->wherePivotNotNull("leido_at")->get() ?? collect();
        $pendientes = $this->comunicado?->destinatarios()->wherePivotNull("leido_at")->get() ?? collect();

        return view("livewire.comunicados.comunicado-lecturas", [
            "leidos" => $leidos,
            "pendientes" => $pendientes,
        ]);
    }
}