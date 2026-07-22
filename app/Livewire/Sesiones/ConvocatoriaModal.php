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
use Illuminate\Validation\Rule;
  
class ConvocatoriaModal extends Component 
{ 
    public ?int $convocatoria_id = null;
    public string $tipo_conv    = ""; 
    public string $titulo       = ""; 
    public string $descripcion  = ""; 
    public string $fecha_sesion = ""; 
    public string $lugar        = ""; 
  
    private function obtenerTiposPermitidos(): array
    {
        $user = Auth::user();

        if ($user && $user->hasRole(['Administrador', 'Secretario Técnico'])) {
            return ['ordinaria', 'extra ordinaria', 'regional', 'multi region', 'municipal'];
        }

        return ['regional', 'multi region', 'municipal'];
    }

    protected function rules(): array 
    {   
        $tiposPermitidos = $this->obtenerTiposPermitidos();

        return [ 
            "tipo_conv"    => ["required", "string", Rule::in($tiposPermitidos)], 
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
            $this->tipo_conv       = $convocatoria->tipo_conv;
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
        try {
            $convocatoria = Convocatoria::create([ 
                "folio"        => FolioService::generarConvocatoria(), 
                //"creada_por"   => Auth::id() ?? 1,
                "creada_por"   => Auth::id(),
                "tipo_conv"    => $this->tipo_conv,
                "titulo"       => $this->titulo, 
                "descripcion"  => $this->descripcion, 
                "fecha_sesion" => $this->fecha_sesion, 
                "lugar"        => $this->lugar, 
                "estado"       => "borrador", 
            ]);

            $this->dispatch("convocatoria-creada", id: $convocatoria->id)->to('sesiones.sesion-modal');
            $this->dispatch("refresh-listado-convocatorias");

            $this->dispatch('swal:alert', [
                'icon'    => 'success',
                'title'   => '¡Creado con éxito!',
                'text'    => "La convocatoria <strong>{$convocatoria->folio}</strong><br><br>ha sido registrada correctamente en el sistema.",
            ]);
            $this->reset(['tipo_conv', 'titulo', 'descripcion', 'fecha_sesion', 'lugar']);

        } catch (\Exception $e) {
       
            $this->dispatch('swal:alert', [
                'icon'    => 'error',
                'title'   => 'Hubo un problema',
                'text'    => 'No se pudo guardar la convocatoria: ' . $e->getMessage(),
            ]);
        }
       /* $this->dispatch("success", "Convocatoria {$convocatoria->folio} creada."); 
        $this->dispatch("convocatoria-creada", id: $convocatoria->id); 
        $this->reset(); */
    } 

    public function posponerConvocatoria(bool $sinFecha = false): void
    {
        if (!$sinFecha) {
            $this->validateOnly('fecha_sesion');
        }
       // $this->validateOnly('fecha_sesion');
        /*if (!$this->convocatoria_id) {
            $this->dispatch("error", "No se ha seleccionado ninguna convocatoria.");
            return;
        }*/

        $convocatoria = Convocatoria::find($this->convocatoria_id);
        try{
        //if ($convocatoria) {
            if ($convocatoria->creada_por !== Auth::id()) {
                $this->dispatch("error", "No tienes permisos. Solo puedes modificar las convocatorias que tú elaboraste.");
                return;
            }
            
            //$convocatoria->fecha_sesion = $this->fecha_sesion; 
            $convocatoria->fecha_sesion = $sinFecha ? null : $this->fecha_sesion; 
            $convocatoria->estado       = "pospuesta"; 
            $convocatoria->save();

            $fechaFormateada = $this->fecha_sesion 
                ? date('d/m/Y \a \l\a\s H:i \H\r\s\.', strtotime($this->fecha_sesion)) 
                : 'Pendiente por definir';

            $this->dispatch('swal:alert', [
                'icon'    => 'success',
                'title'   => '¡Pospuesta con éxito!',
                'text'    => "La convocatoria <strong>{$convocatoria->folio}</strong><br>ha sido pospuesta.<br><br>Nueva fecha: {$fechaFormateada}",
            ]);
            $this->dispatch("refresh-listado-convocatorias");
           // $this->reset(); 
            $this->reset(['fecha_sesion', 'convocatoria_id']);
        } catch (\Exception $e) {
       
            $this->dispatch('swal:alert', [
                'icon'    => 'error',
                'title'   => 'Hubo un problema',
                'text'    => "No fue posible posponer la convocatoria: {$convocatoria->folio}" . $e->getMessage(),
            ]);
            //$this->dispatch("error", "No fue posible posponer la convocatoria.");
        }
    }

    #[On('cargar-convocatoria-a-cancelar')]
    public function cancelarConvocatoria(int $id): void
    {
        $convocatoria = Convocatoria::find($id);
        try{
        //if ($convocatoria) {
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
            $this->dispatch('swal:alert', [
                'icon'    => 'success',
                'title'   => '¡Cancelada con éxito!',
                'text'    => "La convocatoria <strong>{$convocatoria->folio}</strong><br>y su sesión correspondiente han sido canceladas.",
            ]);
            //$this->dispatch("success", "Convocatoria {$convocatoria->folio} y su sesión correspondiente fueron canceladas con éxito.");
            $this->dispatch("refresh-listado-convocatorias");
            $this->reset(); 
        } catch (\Exception $e) {
       
            $this->dispatch('swal:alert', [
                'icon'    => 'error',
                'title'   => 'Hubo un problema',
                'text'    => "No fue posible cancelar la convocatoria: {$convocatoria->folio}" . $e->getMessage(),
            ]);
            //$this->dispatch("error", "No fue posible localizar la convocatoria.");
        }
    }
   
    public function render() 
    { 
        $convocatorias = Convocatoria::orderBy('created_at', 'desc')->get();
        $todosLosTipos = [
            'ordinaria'       => 'Ordinaria',
            'extra ordinaria' => 'Extra Ordinaria',
            'regional'        => 'Regional',
            'multi region'    => 'Multi Region',
            'municipal'       => 'Municipal',
        ];

        $permitidos = $this->obtenerTiposPermitidos();
        $tiposPermitidos = array_intersect_key($todosLosTipos, array_flip($permitidos));

        if ($this->tipo_conv && isset($todosLosTipos[$this->tipo_conv]) && !isset($tiposPermitidos[$this->tipo_conv])) {
            $tiposPermitidos[$this->tipo_conv] = $todosLosTipos[$this->tipo_conv];
        }
        
        return view("livewire.sesiones.convocatoria-modal", [
            'convocatorias'   => $convocatorias, 
            'tiposPermitidos' => $tiposPermitidos,
        ]);
    } 
    public function seleccionarConvocatoria(int $id): void
    {
        $convocatoria = Convocatoria::find($id);
        if ($convocatoria) {
            $this->convocatoria_id = $convocatoria->id;
            $this->tipo_conv       = $convocatoria->tipo_conv;
            $this->titulo          = $convocatoria->titulo;
            $this->descripcion     = $convocatoria->descripcion;
            $this->lugar           = $convocatoria->lugar ?? '';
            $this->fecha_sesion    = $convocatoria->fecha_sesion ? $convocatoria->fecha_sesion->format('Y-m-d\TH:i') : '';
        }
        $this->dispatch("convocatoria-creada", id: $id); 
    }
}