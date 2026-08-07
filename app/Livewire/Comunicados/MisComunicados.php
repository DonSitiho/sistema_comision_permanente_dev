<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use App\Models\ComunicadoCategoria;
use Livewire\Attributes\On;
use Livewire\Component;
class MisComunicados extends Component
{
    public ?int $categoriaId = null;

    #[On("comunicado-leido")]
    public function refrescar(): void {}

    public function filtrarPor(?int $categoriaId): void
    {
        $this->categoriaId = $categoriaId;
    }

    public function abrir(int $comunicadoId): void
    {
        $this->dispatch("abrir-comunicado", id: $comunicadoId);
    }

    public function render()
    {
        $usuario = auth()->user();
        $base = Comunicado::paraUsuario($usuario)->where("estado", "enviado");

        $comunicados = (clone $base)
            ->when($this->categoriaId, fn ($q) => $q->where("categoria_id", $this->categoriaId))
            ->with("categoria")
            ->latest("enviado_at")
            ->get()
            ->map(function (Comunicado $comunicado) use ($usuario) {
                $pivot = $comunicado->destinatarios()->where("users.id", $usuario->id)->first()?->pivot;
                $comunicado->leido_por_mi = filled($pivot?->leido_at ?? null);
                return $comunicado;
            });

        $categorias = ComunicadoCategoria::all()->map(function (ComunicadoCategoria $categoria) use ($base) {
            $categoria->total = (clone $base)->where("categoria_id", $categoria->id)->count();
            return $categoria;
        });

        return view("livewire.comunicados.mis-comunicados", [
            "comunicados" => $comunicados,
            "categorias" => $categorias,
            "totalTodos" => $base->count(),
        ]);
    }
}