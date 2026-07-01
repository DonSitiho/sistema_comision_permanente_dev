<?php
// app/Livewire/Sesiones/SesionModal.php 
  
namespace App\Livewire\Sesiones; 
  
use App\Models\Sesion; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate; 
use Livewire\Component; 
  
class SesionModal extends Component 
{ 
    public ?int $convocatoria_id = null; 
    public string $tipo             = "presencial"; 
    public string $enlace_videoconf = ""; 
    public string $plataforma       = ""; 
  
    public function getRequiereVideoconfProperty(): bool 
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
  
    public function submit(): void 
    { 
        Gate::authorize("create", Sesion::class); 
        $this->validate(); 
  
        Sesion::create([ 
            "convocatoria_id"  => $this->convocatoria_id, 
            "tipo"             => $this->tipo, 
            "estado"           => "convocada", 
            "enlace_videoconf" => $this->requiereVideoconf ? $this->enlace_videoconf : null, 
            "plataforma"       => $this->requiereVideoconf ? $this->plataforma : null, 
            "creada_por"       => Auth::id(), 
        ]); 
  
        $this->dispatch("success", "Sesion registrada."); 
        $this->reset(); 
    } 
  
    public function render() 
    { 
        return view("livewire.sesiones.sesion-modal"); 
    } 
    <select wire:model.live="tipo" class="form-select mb-4"> 
        <option value="presencial">Presencial</option> 
        <option value="virtual">Virtual</option> 
        <option value="mixta">Mixta</option> 
    </select> 
  
    @if ($this->requiereVideoconf) 
        <div class="mb-4"> 
            <label class="form-label">Plataforma</label> 
            <select wire:model="plataforma" class="form-select"> 
                <option value="">Seleccionar...</option> 
                <option value="zoom">Zoom</option> 
                <option value="meet">Google Meet</option> 
                <option value="webex">Webex</option> 
                <option value="teams">Microsoft Teams</option> 
                <option value="otro">Otro</option> 
            </select> 
        </div> 
        <div class="mb-4"> 
            <label class="form-label">Enlace de acceso</label> 
            <input wire:model="enlace_videoconf" type="url" class="form-control" 
                placeholder="https://zoom.us/j/..."> 
            <div class="form-text"> 
                Genera la reunion en tu cuenta personal y pega el enlace aqui. 
            </div> 
            @error("enlace_videoconf") 
                <span class="text-danger small">{{ $message }}</span> 
            @enderror 
        </div> 
    @endif 
} 
