<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use App\Services\ComunicadoService;
use Livewire\Attributes\On;
use Livewire\Component;
class ComunicadoVerMas extends Component
{
    public ?Comunicado $comunicado = null;

    #[On("abrir-comunicado")]
    public function abrir(int $id, ComunicadoService $servicio): void
    {
        $this->comunicado = Comunicado::with("categoria")->findOrFail($id);

        if (!$this->comunicado->obligatorio) {
            $servicio->marcarLeido($this->comunicado, auth()->user());
            $this->dispatch("comunicado-leido", id: $this->comunicado->id);
        }

        $this->dispatch("abrir-modal-ver-mas");
    }

    public function aceptar(ComunicadoService $servicio): void
    {
        if (!$this->comunicado) {
            return;
        }
        $servicio->aceptar($this->comunicado, auth()->user());
        $this->dispatch("comunicado-leido", id: $this->comunicado->id);
        $this->dispatch("cerrar-modal-ver-mas");
    }

    public function render()
    {
        return view("livewire.comunicados.comunicado-ver-mas");
    }
}