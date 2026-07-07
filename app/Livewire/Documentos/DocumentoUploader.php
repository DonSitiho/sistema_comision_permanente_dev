<?php
// app/Livewire/Documentos/DocumentoUploader.php

namespace App\Livewire\Documentos;
  
use App\Models\Documento; 
use App\Services\CifradoService; 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\Auth; 
use Livewire\WithFileUploads;
use Livewire\Component;

class DocumentoUploader extends Component
{
    use WithFileUploads; 
  
    public Model $entidad; 
    public $archivo; 
    public string $categoria = ""; 
  
    protected function rules(): array 
    { 
        return [ "archivo" => "required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png|max:10240", ]; 
    } 
  
    public function subir(): void 
    { 
        $this->validate(); 
  
        $contenido = file_get_contents($this->archivo->getRealPath()); 
        $rutaDestino = "sesion_" . $this->entidad->id . "/" . uniqid() . ".enc"; 
  
        $rutaFinal = app(CifradoService::class) 
            ->cifrarYAlmacenar($contenido, $rutaDestino); 
  
        $this->entidad->documentos()->create([ 
            "nombre_original" => $this->archivo->getClientOriginalName(), 
            "ruta_cifrada"     => $rutaFinal, 
            "mime_type"        => $this->archivo->getMimeType(), 
            "tamano"           => $this->archivo->getSize(), 
            "subido_por"       => Auth::id(), 
            "categoria"        => $this->categoria ?: null, 
        ]); 
  
        $this->dispatch("success", "Documento cargado correctamente."); 
        $this->reset(["archivo", "categoria"]); 
    }
    public function render() 
    { 
        return view("livewire.documentos.documento-uploader", [ "documentos" => $this->entidad->documentos, ]); 
    }
}
