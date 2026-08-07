<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use App\Services\ComunicadoService;
use Livewire\Component;
class ModalObligatorio extends Component
{
    public array $cola = [];
    public ?int $actual = null;

    public function mount(): void
    {
        $this->cargarCola();
    }

    public function cargarCola(): void
    {
        $this->cola = Comunicado::obligatoriosPendientes(auth()->user())->pluck("id")->all();
        $this->actual = $this->cola[0] ?? null;
    }

    public function aceptar(ComunicadoService $servicio): void
    {
        if (!$this->actual) {
            return;
        }
        $servicio->aceptar(Comunicado::findOrFail($this->actual), auth()->user());
        $this->dispatch("comunicado-leido", id: $this->actual);
        $this->cargarCola();
    }

    public function render()
    {
        $comunicadoActual = $this->actual ? Comunicado::with("categoria")->find($this->actual) : null;

        return view("livewire.comunicados.modal-obligatorio", ["comunicadoActual" => $comunicadoActual]);
    }
}