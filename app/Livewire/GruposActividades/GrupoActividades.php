<?php

namespace App\Livewire\GruposActividades;
use App\Models\GrupoActividad;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
//use App\Models\User;

class GrupoActividades extends Component
{
    public string $nombre = '';
    public string $descripcion = '';
    //public array $actividades = [];
    public ?int $grupo_id = null;
    public ?int $convocatoria_id = null;
    public bool $grupoGuardado = false;

    protected function rules(): array
    {
        return [
            'nombre'        => 'required|string|max:100',
            'descripcion'   => 'nullable|string|max:500',
        ];
    }

    public function crearGrupo()
    {
        $this->validate();
        try {
            $grupo = GrupoActividad::create([
                    'dueno_id'          => Auth::id(),
                    'convocatoria_id'   => $this->convocatoria_id,
                    'nombre'            => $this->nombre, 
                    'descripcion'       => $this->descripcion,
                    'estatus'           => 'pendiente',
                    'estatus_manual'    => '0',
                ]);
            $this->grupo_id = $grupo->id;
            $this->grupoGuardado = true;
            $this->dispatch('swal:alert', [
                    'icon'  => 'success',
                    'title' => '¡Grupo de Actividades Registrado!',
                    'text'  => 'El grupo de actividades se registró correctamente.',
                ]);
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'icon'  => 'error',
                'title' => 'Hubo un error',
                'text'  => 'Error al guardar el grupo de actividades: ' . $e->getMessage(),
            ]);
        }
    }

    public function cancelar()
    {
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.grupos-actividades.grupo-actividades');
    }
}
