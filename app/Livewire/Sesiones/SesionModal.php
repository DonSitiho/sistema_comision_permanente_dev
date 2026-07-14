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
  
class SesionModal extends Component 
{ 
    public ?int $convocatoria_id = null; 
    public ?Convocatoria $convocatoriaReciente = null;
    public string $tipo             = "presencial"; 
    public string $enlace_videoconf = ""; 
    public string $plataforma       = ""; 
    public bool $esLectura = false;

    public function requiereVideoconf(): bool 
    { 
        return in_array($this->tipo, ["virtual", "mixta"]); 
    }
  
    protected function rules(): array 
    { 
        return [ 
            "tipo" => "required|in:presencial,virtual,mixta", 
            "enlace_videoconf" => "required_if:tipo,virtual,mixta|nullable|url|max:500", 
            "plataforma"       => "required_if:tipo,virtual,mixta|nullable|in:zoom,meet,webex,teams,otro", 
        ]; 
    } 

    #[On('convocatoria-creada')]
    //Asignar una sesión a una convocatoria
    public function asignarConvocatoria(int $id): void
    {
        $this->convocatoria_id = $id;
        $this->convocatoriaReciente = Convocatoria::find($id);

        $sesionExistente = Sesion::where('convocatoria_id', $id)
                                 ->where('estado', 'convocada')
                                 ->first();
        if ($sesionExistente) {
            $this->tipo = $sesionExistente->tipo;
            $this->enlace_videoconf = $sesionExistente->enlace_videoconf ?? '';
            $this->plataforma = $sesionExistente->plataforma ?? '';
            $this->esLectura = true;
        } else {
            $this->reset(['tipo', 'enlace_videoconf', 'plataforma']);
            $this->esLectura = false;
        }
    }

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

            $this->dispatch("success", "Sesión cancelada. La convocatoria está lista para una nueva configuración.");
            $this->dispatch("refresh-listado-convocatorias");
            
            $idTemporal = $this->convocatoria_id;
            $this->reset(['tipo', 'enlace_videoconf', 'plataforma', 'convocatoria_id']);
            $this->esLectura = false;

            $this->asignarConvocatoria($idTemporal);
        } else {
            $this->dispatch("error", "No se encontró ninguna sesión activa vinculada a esta convocatoria.");
        }
    }
    
    /*public function submit(): void 
    { 
        Gate::authorize("create", Sesion::class); 
        $this->validate(); 
  
        DB::transaction(function () {
            //Crea la sesión vinculando el convocatoria_id heredado de la fila
            Sesion::create([ 
                "convocatoria_id"  => $this->convocatoria_id, 
                "tipo"             => $this->tipo, 
                "estado"           => "convocada", 
                "enlace_videoconf" => $this->requiereVideoconf() ? $this->enlace_videoconf : null, 
                "plataforma"       => $this->requiereVideoconf() ? $this->plataforma : null, 
                "creada_por"       => Auth::id() ?? 1, 
            ]); 
      
            //Cambia el estado de la Convocatoria a 'enviada' una vez que se ha creado la sesión
            Convocatoria::where('id', $this->convocatoria_id)->update([
                'estado' => 'enviada'
            ]);
        });

        $this->dispatch("success", "Sesión registrada y convocatoria actualizada."); 
        $this->dispatch('refreshTable')->to(\App\Livewire\Sesiones\ConvocatoriaListado::class); 
     $this->js('window.cerrarModalesMetronic();');
     
        $this->reset(['tipo', 'enlace_videoconf', 'plataforma', 'convocatoria_id']); 
    } */
   public function submit(): void 
    { 
        try {
            Gate::authorize("create", Sesion::class); 
            $this->validate(); 
      
            DB::transaction(function () {
                Sesion::create([ 
                    "convocatoria_id"  => $this->convocatoria_id, 
                    "tipo"             => $this->tipo, 
                    "estado"           => "convocada", 
                    "enlace_videoconf" => $this->requiereVideoconf() ? $this->enlace_videoconf : null, 
                    "plataforma"       => $this->requiereVideoconf() ? $this->plataforma : null, 
                    "creada_por"       => Auth::id() ?? 1, 
                ]); 
          
                Convocatoria::where('id', $this->convocatoria_id)->update([
                    'estado' => 'enviada'
                ]);
            });

            $this->dispatch("success", "Sesión registrada y convocatoria actualizada."); 
            $this->dispatch('refreshTable')->to(\App\Livewire\Sesiones\ConvocatoriaListado::class); 
            
            // Cerrar modales inmediatamente al finalizar con éxito
            $this->js('window.cerrarModalesMetronic();');
            $this->reset(['tipo', 'enlace_videoconf', 'plataforma', 'convocatoria_id']); 

        } catch (\Exception $e) {
            // Si ocurre el error 500, forzamos el cierre preventivo para no congelar la app
            $this->js('window.cerrarModalesMetronic();');
            
            $this->dispatch("error", "Error al procesar la solicitud: " . $e->getMessage());
        }
    }
  
    public function render() 
    { 
        return view("livewire.sesiones.sesion-modal"); 
    } 
}