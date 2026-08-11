<?php
namespace App\Livewire\Comunicados;
use App\Models\Comunicado;
use App\Models\ComunicadoCategoria;
use App\Models\Region;
use App\Models\User;
use App\Services\ComunicadoService;
use Livewire\Component;
class ComunicadoForm extends Component
{
    public ?int $comunicadoId = null;
    public string $titulo = "";
    public ?int $categoria_id = null;
    public string $cuerpo = "";
    public bool $obligatorio = false;
    public string $alcance = "general"; // general | region | lista
    public array $regionesSeleccionadas = []; // IDs de Dependencia (ver nota en el Paso 3)
    public string $busquedaUsuario = "";
    public array $usuariosSeleccionados = [];

    public function mount(?int $comunicadoId = null): void
    {
        if ($comunicadoId) {
            $comunicado = Comunicado::findOrFail($comunicadoId);
            $this->authorize("update", $comunicado);
            $this->comunicadoId = $comunicado->id;
            $this->titulo = $comunicado->titulo;
            $this->categoria_id = $comunicado->categoria_id;
            $this->cuerpo = $comunicado->cuerpo;
            $this->obligatorio = $comunicado->obligatorio;
            $this->alcance = $comunicado->alcance;
            $this->regionesSeleccionadas = $comunicado->criterio["regionIds"] ?? [];
            $this->usuariosSeleccionados = $comunicado->criterio["userIds"] ?? [];
        } else {
            $this->authorize("create", Comunicado::class);
        }
    }

    protected function rules(): array
    {
        return [
            "titulo" => "required|string|max:200",
            "categoria_id" => "required|exists:f3cmu_categorias,id",
            "cuerpo" => "required|string",
            "alcance" => "required|in:general,region,lista",
            "regionesSeleccionadas" => "required_if:alcance,region|array",
            "usuariosSeleccionados" => "required_if:alcance,lista|array",
        ];
    }

  public function toggleRegion(int $regionId): void
    {
        if (in_array($regionId, $this->regionesSeleccionadas, true)) {
            $this->regionesSeleccionadas = array_values(array_diff($this->regionesSeleccionadas, [$regionId]));
        } else {
            $this->regionesSeleccionadas[] = $regionId;
        }
    }

    public function agregarUsuario(int $usuarioId): void
    {
        if (!in_array($usuarioId, $this->usuariosSeleccionados, true)) {
            $this->usuariosSeleccionados[] = $usuarioId;
        }
        $this->busquedaUsuario = "";
    }

    public function quitarUsuario(int $usuarioId): void
    {
        $this->usuariosSeleccionados = array_values(array_diff($this->usuariosSeleccionados, [$usuarioId]));
    }

    protected function datosFormulario(): array
    {
        return [
            "id" => $this->comunicadoId,
            "categoria_id" => $this->categoria_id,
            "emitido_por" => auth()->id(),
            "titulo" => $this->titulo,
            "cuerpo" => $this->cuerpo,
            "obligatorio" => $this->obligatorio,
            "alcance" => $this->alcance,
            "criterio" => $this->criterio(),
        ];
    }

    protected function criterio(): array
    {
        return [
            "alcance" => $this->alcance,
            "regionIds" => $this->alcance === "region" ? $this->regionesSeleccionadas : [],
            "userIds" => $this->alcance === "lista" ? $this->usuariosSeleccionados : [],
        ];
    }

    public function guardarBorrador(ComunicadoService $servicio): void
{
    try {
        $this->validate();

        $comunicado = $servicio->guardarBorrador($this->datosFormulario());
        $this->comunicadoId = $comunicado->id;

        $this->dispatch('comunicado-guardado', id: $comunicado->id);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('DEBUG guardarBorrador: ' . get_class($e) . ' - ' . $e->getMessage(), [
            'validacion' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null,
        ]);
        throw $e;
    }
}

public function enviar(ComunicadoService $servicio): void
{
    try {
        $this->validate();
        $this->authorize('create', Comunicado::class);

        $comunicado = $servicio->guardarBorrador($this->datosFormulario());
        $servicio->enviar($comunicado, $this->criterio());

        $this->dispatch('comunicado-enviado', id: $comunicado->id);

        $this->reset([
            'comunicadoId', 'titulo', 'categoria_id', 'cuerpo', 'obligatorio',
            'alcance', 'regionesSeleccionadas', 'busquedaUsuario', 'usuariosSeleccionados',
        ]);
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('DEBUG enviar: ' . get_class($e) . ' - ' . $e->getMessage(), [
            'validacion' => $e instanceof \Illuminate\Validation\ValidationException ? $e->errors() : null,
        ]);
        throw $e;
    }
}

    public function render()
    {
        $usuariosEncontrados = trim($this->busquedaUsuario) === ""
            ? collect()
            : User::where("name", "like", "%{$this->busquedaUsuario}%")->limit(10)->get();

        return view("livewire.comunicados.comunicado-form", [
            "categorias" => ComunicadoCategoria::orderBy("nombre")->get(),
            "regiones" => Region::orderBy("nombre")->get(),
            "usuariosEncontrados" => $usuariosEncontrados,
            "usuariosSeleccionadosInfo" => User::whereIn("id", $this->usuariosSeleccionados)->get()->keyBy("id"),
        ]);
    }
}