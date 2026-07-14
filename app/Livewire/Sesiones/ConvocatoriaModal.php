<?php 
// app/Livewire/Sesiones/ConvocatoriaModal.php 
  
namespace App\Livewire\Sesiones; 

use App\Models\Convocatoria; 
use App\Models\Sesion;
use App\Services\FolioService; 
use App\Services\NotificacionService;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Support\Facades\DB;
use Livewire\Component; 
use Livewire\Attributes\On;
  
class ConvocatoriaModal extends Component 
{ 
    public ?int $convocatoria_id = null;
    public string $titulo       = ""; 
    public string $descripcion  = ""; 
    public string $fecha_sesion = ""; 
    public string $lugar        = ""; 
  
    protected function rules(): array 
    { 
        return [ 
            "titulo"       => "required|string|max:200", 
            "descripcion"  => "nullable|string", 
            "fecha_sesion" => "required|date|after_or_equal:" . now()->subMinute()->toDateTimeString(),
            "lugar"        => "nullable|string|max:255", 
        ]; 
    } 
    
    #[On('cargar-convocatoria-a-posponer')]
    public function cargarDatos(int $id): void
    {
        $convocatoria = Convocatoria::find($id);
        if ($convocatoria) {
            $this->convocatoria_id = $convocatoria->id;
            $this->titulo          = $convocatoria->titulo;
            $this->descripcion     = $convocatoria->descripcion ?? '';
            $this->lugar           = $convocatoria->lugar ?? '';
            $this->fecha_sesion    = $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('Y-m-d\TH:i') : '';
        }
    }

    public function submit(): void 
    { 
        //Gate::authorize("create", Convocatoria::class); 
        $this->validate();
  
        $convocatoria = Convocatoria::create([ 
            "folio"        => FolioService::generarConvocatoria(), 
            "creada_por"   => Auth::id() ?? 1,
            //"creada_por"   => Auth::id(),
            "titulo"       => $this->titulo, 
            "descripcion"  => $this->descripcion, 
            "fecha_sesion" => $this->fecha_sesion, 
            "lugar"        => $this->lugar, 
            "estado"       => "borrador", 
        ]); 
        $this->dispatch("success", "Convocatoria {$convocatoria->folio} creada."); 
        $this->dispatch("convocatoria-creada", id: $convocatoria->id); 
        $this->reset(); 
    } 

    public function posponerConvocatoria(): void
    {
        $this->validateOnly('fecha_sesion');
        /*if (!$this->convocatoria_id) {
            $this->dispatch("error", "No se ha seleccionado ninguna convocatoria.");
            return;
        }*/

        $convocatoria = Convocatoria::find($this->convocatoria_id);

        if ($convocatoria) {
            if ($convocatoria->creada_por !== Auth::id()) {
                $this->dispatch("error", "No tienes permisos. Solo puedes modificar las convocatorias que tú elaboraste.");
                return;
            }

            $convocatoria->fecha_sesion = $this->fecha_sesion; 
            $convocatoria->estado       = "pospuesta"; 
            $convocatoria->save();

            //Generación de notificación
            /*if (class_exists(NotificacionService::class)) {
                NotificacionService::crearNotificacion(
                    user_id: $convocatoria->creada_por,
                    tipo: 'sistema',
                    titulo: 'Convocatoria Pospuesta',
                    mensaje: "La convocatoria con folio {$convocatoria->folio} ha sido pospuesta para el " . date('d/m/Y H:i', strtotime($this->fecha_sesion)),
                   // url_destino:
                );
            }

            $this->dispatch('notificacion-nueva');*/
            $this->dispatch("success", "Convocatoria {$convocatoria->folio} pospuesta con éxito.");
            //$this->dispatch("convocatoria-pospuesta", id: $convocatoria->id);
            $this->dispatch("refresh-listado-convocatorias");
            $this->reset(); 
        } else {
            $this->dispatch("error", "No fue posible posponer la convocatoria.");
        }
    }

    #[On('cargar-convocatoria-a-cancelar')]
    public function cancelarConvocatoria(int $id): void
    {
        $convocatoria = Convocatoria::find($id);

        if ($convocatoria) {
            if ($convocatoria->creada_por !== Auth::id()) {
                $this->dispatch("error", "No tienes permisos. Solo puedes cancelar las convocatorias que tú elaboraste.");
                return;
            }

            DB::transaction(function () use ($convocatoria, $id) {
                // Se cancela la convocatoria
                $convocatoria->update([
                    'estado' => 'cancelada'
                ]);

                // Si tiene sesión asignada, también se cancela
                Sesion::where('convocatoria_id', $id)->update([
                    'estado' => 'cancelada'
                ]);
            });

            $this->dispatch("success", "Convocatoria {$convocatoria->folio} y su sesión correspondiente fueron canceladas con éxito.");
            $this->dispatch("refresh-listado-convocatorias");
            $this->reset(); 
        } else {
            $this->dispatch("error", "No fue posible localizar la convocatoria.");
        }
    }
    /*#[On('cargar-convocatoria-a-cancelar')]
    public function cancelarConvocatoria(int $id): void
    {
        $convocatoria = Convocatoria::find($id);

        if ($convocatoria) {
            // Validamos que el usuario autenticado sea el creador
            if ($convocatoria->creada_por !== Auth::id()) {
                $this->dispatch("error", "No tienes permisos. Solo puedes cancelar las convocatorias que tú elaboraste.");
                return;
            }

            // Usamos la transacción para asegurar consistencia atómica
            DB::transaction(function () use ($convocatoria, $id) {
                // 1. Actualizamos el estado de la convocatoria a cancelada
                $convocatoria->update([
                    'estado' => 'cancelada'
                ]);

                // 2. CORREGIDO: Buscamos la sesión usando $id o $convocatoria->id
                // Si la convocatoria tiene una sesión asignada, también la cancelamos
                \App\Models\Sesion::where('convocatoria_id', $id)->update([
                    'estado' => 'cancelada'
                ]);
            });

            // Opcional: Descomenta esto si deseas reactivar tus notificaciones automatizadas
            /* if (class_exists(NotificacionService::class)) {
                NotificacionService::crearNotificacion(
                    user_id: $convocatoria->creada_por,
                    tipo: 'sistema',
                    titulo: 'Convocatoria Cancelada',
                    mensaje: "La convocatoria con folio {$convocatoria->folio} y su sesión programada han sido canceladas.",
                    url_destino: route('historial-notificaciones')
                );
                $this->dispatch('notificacion-nueva');
            }*/

    /*        $this->dispatch("success", "Convocatoria {$convocatoria->folio} y su sesión correspondiente fueron canceladas con éxito.");
            $this->dispatch("refresh-listado-convocatorias");
            $this->reset(); 
        } else {
            $this->dispatch("error", "No fue posible localizar la convocatoria.");
        }
    }*/
            
    public function render() 
    { 
        $convocatorias = Convocatoria::orderBy('created_at', 'desc')->get();
        return view("livewire.sesiones.convocatoria-modal", [
            'convocatorias' => $convocatorias
        ]);

        //return view("livewire.sesiones.convocatoria-modal"); 
    } 
    public function seleccionarConvocatoria(int $id): void
    {
        $convocatoria = Convocatoria::find($id);
        if ($convocatoria) {
            $this->convocatoria_id = $convocatoria->id;
            $this->titulo          = $convocatoria->titulo;
            $this->descripcion     = $convocatoria->descripcion;
            $this->lugar           = $convocatoria->lugar ?? '';
            $this->fecha_sesion    = $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('Y-m-d\TH:i') : '';
        }
        $this->dispatch("convocatoria-creada", id: $id); 
    }
}