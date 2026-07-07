<?php 
// app/Livewire/Sesiones/ConvocatoriaModal.php 
  
namespace App\Livewire\Sesiones; 
  
use App\Models\Convocatoria; 
use App\Services\FolioService; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate; 
use Livewire\Component; 
  
class ConvocatoriaModal extends Component 
{ 
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
        $this->dispatch("convocatoria-creada", id: $id); 
    }
}