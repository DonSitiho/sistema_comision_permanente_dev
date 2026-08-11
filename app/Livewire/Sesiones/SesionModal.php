<?php
// app/Livewire/Sesiones/SesionModal.php
namespace App\Livewire\Sesiones; 
  
use App\Models\Sesion; 
use App\Models\Convocatoria; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\DB;
use Livewire\Component; 
use Livewire\Attributes\On; 
use App\Models\User;

class SesionModal extends Component 
{ 
    public string $buscar_invitado = "";
    public ?int $convocatoria_id = null; 
    public ?Convocatoria $convocatoriaReciente = null;
    public string $tipo                 = ""; 
    public string $enlace_videoconf     = ""; 
    public string $plataforma           = ""; 
    public string $hora_inicio          = ""; 
    public string $hora_fin             = ""; 
    public string $descripcion_sesion   = ""; 
    public string $num_enlace_videoconf = ""; 
    public string $cod_acceso_videoconf = ""; 
    public string $region           = ""; 
    public bool $esLectura = false;
    public bool $mostrarModalInvitados = false;
    public string $regionFiltro = "";
    public array $invitadosSeleccionados = [];

    public function abrirModalInvitados(): void
    {
        $this->mostrarModalInvitados = true;
    }
    public function cerrarModalInvitados(): void
    {
        $this->mostrarModalInvitados = false;
    }
   
    protected function rules(): array 
    { 
        return [ 
            "tipo" => "required|in:presencial,virtual,mixta", 
            "enlace_videoconf"     => "required_if:tipo,virtual,mixta|nullable|url|max:500", 
            "plataforma"           => "required_if:tipo,virtual,mixta|nullable|in:zoom,meet,webex,teams,otro", 
            "num_enlace_videoconf" => "required_if:tipo,virtual,mixta|nullable|numeric",
            "cod_acceso_videoconf" => "required_if:tipo,virtual,mixta|nullable|string|max:10", 
            'hora_inicio'          => 'required_if:tipo,virtual,mixta|nullable|date_format:H:i',
            'hora_fin'             => 'required_if:tipo,virtual,mixta|nullable|date_format:H:i|after:hora_inicio',
            "descripcion_sesion"   => "nullable|string|max:500",
            "invitadosSeleccionados"   => "required|array|min:1",
        ]; 
    } 
    public function requiereVideoconf(): bool 
    { 
        return in_array($this->tipo, ["virtual", "mixta"]); 
    }
    protected function messages(): array
    {
        return [
            'invitadosSeleccionados.required' => 'Debes asignar al menos un invitado a la sesión.',
            'invitadosSeleccionados.min'      => 'Debes asignar al menos 1 invitado a la sesión.',
        ];
    }

    #[On('convocatoria-creada')]
    //Asignar una sesión a una convocatoria
    public function asignarConvocatoria(int $id): void
    {
        $this->convocatoria_id = $id;
        $this->convocatoriaReciente = Convocatoria::find($id);

        $sesionExistente = Sesion::where('convocatoria_id', $id)
                                 ->whereIn('estado', ['convocada', 'realizada', 'en_curso'])
                                 ->first();
        if ($sesionExistente) {
            $this->tipo = $sesionExistente->tipo;
            $this->enlace_videoconf = $sesionExistente->enlace_videoconf ?? '';
            $this->plataforma = $sesionExistente->plataforma ?? '';
           

            $this->descripcion_sesion     = $sesionExistente->descripcion_sesion ?? '';
            $this->hora_inicio            = $sesionExistente->hora_inicio ? \Carbon\Carbon::parse($sesionExistente->hora_inicio)->format('H:i') . ' Hrs.' : '00:00 Hrs.';
            $this->hora_fin               = $sesionExistente->hora_fin ? \Carbon\Carbon::parse($sesionExistente->hora_fin)->format('H:i') . ' Hrs.' : '00:00 Hrs.';
            $this->num_enlace_videoconf   = $sesionExistente->num_enlace_videoconf ?? '';
            $this->cod_acceso_videoconf   = $sesionExistente->cod_acceso_videoconf ?? '';
            //$this->invitadosSeleccionados = $sesionExistente->invitados->pluck('id')->toArray();
            $this->esLectura = true;
        } else {
            $this->reset(['tipo', 'enlace_videoconf', 'plataforma','hora_inicio','hora_fin','num_enlace_videoconf','cod_acceso_videoconf','descripcion_sesion','invitadosSeleccionados']);
            $this->esLectura = false;
        }
    }

    public function updatedHoraInicio(): void
    {
        $this->validarRangoHoras();
    }

    public function updatedHoraFin(): void
    {
        $this->validarRangoHoras();
    }

    private function validarRangoHoras(): void
    {
        if (!empty($this->hora_inicio) && !empty($this->hora_fin)) {
            $inicio = trim(str_replace(['Hrs.', 'Hrs'], '', $this->hora_inicio));
            $fin    = trim(str_replace(['Hrs.', 'Hrs'], '', $this->hora_fin));

            // Validar si la hora fin es menor o igual a la de inicio
            if ($fin <= $inicio) {
                $this->addError('hora_fin', 'La hora final debe ser posterior a la hora inicial.');
            } else {
                $this->resetErrorBag('hora_fin');
            }
        }
    }

    public function toggleInvitado(int $userId): void
    {
        if (in_array($userId, $this->invitadosSeleccionados)) {
            $this->invitadosSeleccionados = array_diff($this->invitadosSeleccionados, [$userId]);
        } else {
            $this->invitadosSeleccionados[] = $userId;
        }
    }

    public function seleccionarPorRegion(string $region): void
    {
        $this->regionFiltro = $region;
        $usersRegion = User::where('region', $region)->pluck('id')->toArray();
        $this->invitadosSeleccionados = array_unique(array_merge($this->invitadosSeleccionados, $usersRegion));
    }

    public function seleccionarTodos(): void
    {
        $this->invitadosSeleccionados = User::pluck('id')->toArray();
    }

    //public function abrirModalInvitados(): void { $this->mostrarModalInvitados = true; }
    //public function cerrarModalInvitados(): void { $this->mostrarModalInvitados = false; }

    #[On('notificar-cancelar-sesion-directa')]
    public function procesarCancelacionDesdeMenu(int $id): void
    {
        $this->convocatoria_id = $id;
        $this->cancelarSesionActual();
    }

    // Cancelar la sesión actual y regresar la convocatoria a estado 'borrador'
    public function cancelarSesionActual(): void
    {
        if (!$this->convocatoria_id) {
            $this->dispatch("error", "No hay ninguna convocatoria seleccionada.");
            return;
        }
        $sesion = Sesion::where('convocatoria_id', $this->convocatoria_id)
                        ->where('estado', 'convocada')
                        ->first();
        if ($sesion) {
            try{
                if ($sesion->creada_por !== Auth::id()) {
                    $this->dispatch("error", "No tienes permisos para cancelar las sesiones creadas por otros usuarios.");
                    return;
                }

                DB::transaction(function () use ($sesion) {
                    $sesion->update([
                        'estado' => 'cancelada'
                    ]);
                    Convocatoria::where('id', $this->convocatoria_id)->update([
                        'estado' => 'borrador'
                    ]);
                });
                
                $this->dispatch('swal:alert', [
                    'icon'    => 'success',
                    'title'   => '¡Cancelada con éxito!',
                    'text'    => "Sesión cancelada.<br><br>La convocatoria está lista para la configuración de una nueva sesión.",
                ]);
                $this->dispatch("refresh-listado-convocatorias");
                
                $idTemporal = $this->convocatoria_id;
                //$this->reset(['tipo', 'enlace_videoconf', 'plataforma', 'convocatoria_id']);
                $this->reset(['tipo', 'enlace_videoconf', 'plataforma','hora_inicio','hora_fin','num_enlace_videoconf','cod_acceso_videoconf','descripcion_sesion']);
                $this->esLectura = false;

                $this->asignarConvocatoria($idTemporal);
            } catch (\Exception $e) {
        
                $this->dispatch('swal:alert', [
                    'icon'    => 'error',
                    'title'   => 'Hubo un problema',
                    'text'    => "Error al cancelar la sesión",
                ]);
                
            }
        } /*else {
            $this->dispatch("error", "No se encontró ninguna sesión activa vinculada a esta convocatoria.");
        }*/
    }

    public function submit(): void 
    { 
        try {
            Gate::authorize("create", Sesion::class); 
            $this->validate(); 
            
            DB::transaction(function () {
                Sesion::create([ 
                    "convocatoria_id"      => $this->convocatoria_id, 
                    "tipo"                 => $this->tipo, 
                    "estado"               => "convocada", 
                    "descripcion_sesion"   => $this->descripcion_sesion ?: null,
                    "hora_inicio"          => $this->hora_inicio ?: null,
                    "hora_fin"             => $this->hora_fin ?: null,
                    "enlace_videoconf"     => $this->requiereVideoconf() ? $this->enlace_videoconf : null, 
                    "plataforma"           => $this->requiereVideoconf() ? $this->plataforma : null, 
                    "num_enlace_videoconf" => $this->requiereVideoconf() ? ($this->num_enlace_videoconf ?: null) : null,
                    "cod_acceso_videoconf" => $this->requiereVideoconf() ? ($this->cod_acceso_videoconf ?: null) : null,
                    "creada_por"           => Auth::id(),
                ]); 
                $sesion->invitados()->sync($this->invitadosSeleccionados);
                Convocatoria::where('id', $this->convocatoria_id)->update([
                    'estado' => 'enviada'
                ]);
            });

            $this->dispatch('refreshTable')->to(\App\Livewire\Sesiones\ConvocatoriaListado::class); 
            $this->js('window.cerrarModalesMetronic();');

            $folio = $this->convocatoriaReciente ? $this->convocatoriaReciente->folio : '';
            $this->dispatch('swal:alert', [
                'icon'    => 'success',
                'title'   => '¡Creado con éxito!',
                'text'    => "La sesión ha sido asignada correctamente a la <br><br>convocatoria <strong>{$folio}</strong>.",
            ]);

            $this->reset(['tipo', 'enlace_videoconf', 'plataforma', 'hora_inicio', 'hora_fin', 'num_enlace_videoconf', 'cod_acceso_videoconf', 'descripcion_sesion']);
        } catch (\Exception $e) {
            $this->dispatch('swal:alert', [
                'icon'    => 'error',
                'title'   => 'Hubo un problema',
                'text'    => "Error al crear la sesión: " . $e->getMessage(),
            ]);   
        } 
    }
  
    public function render() 
    { 
        $usuariosBusqueda = [];
        if ($this->mostrarModalInvitados && !$this->esLectura) {
            $query = User::query();
            if ($this->buscar_invitado) {
                $query->where(function($q) {
                    $q->where('name', 'LIKE', '%' . $this->buscar_invitado . '%');
                      //->orWhere('email', 'LIKE', '%' . $this->buscar_invitado . '%');
                });
            }
            $usuariosBusqueda = $query->take(10)->get();
        }

        $invitadosAsignados = User::whereIn('id', $this->invitadosSeleccionados)->get();

        return view("livewire.sesiones.sesion-modal", [
            'usuariosBusqueda'   => $usuariosBusqueda,
            'invitadosAsignados' => $invitadosAsignados,
        ]);
    }
        // 1. Buscador de invitados por nombre
    /*    if ($this->buscar_invitado) {
            $query->where(function($q) {
                $q->where('name', 'LIKE', '%' . $this->buscar_invitado . '%');
            });
        }
        return view("livewire.sesiones.sesion-modal"); 
    } */
}