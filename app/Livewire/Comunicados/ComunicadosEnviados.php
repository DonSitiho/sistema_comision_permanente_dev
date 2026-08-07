<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
class ComunicadosEnviados extends Component
{
    use WithPagination;
    public string $busqueda = "";
    public string $estado = "";
    public ?string $fecha = null;
    public ?int $seleccionadoId = null;

    public function mount(): void
    {
        $this->authorize("create", Comunicado::class);
    }

    public function updated($propiedad): void
    {
        if (in_array($propiedad, ["busqueda", "estado", "fecha"], true)) {
            $this->resetPage();
        }
    }

    public function limpiar(): void
    {
        $this->reset(["busqueda", "estado", "fecha"]);
        $this->resetPage();
    }

    public function seleccionar(int $comunicadoId): void
    {
        $this->seleccionadoId = $comunicadoId;
        $this->dispatch("ver-lecturas", id: $comunicadoId);
    }

    #[On("comunicado-enviado")]
    public function refrescar(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $comunicados = Comunicado::query()
            ->with("categoria")
            ->where("emitido_por", auth()->id())
            ->when($this->busqueda, fn ($q) => $q->where("titulo", "like", "%{$this->busqueda}%"))
            ->when($this->estado, fn ($q) => $q->where("estado", $this->estado))
            ->when($this->fecha, fn ($q) => $q->whereDate("enviado_at", $this->fecha))
            ->latest("id")
            ->paginate(10);

        return view("livewire.comunicados.comunicados-enviados", ["comunicados" => $comunicados]);
    }
}