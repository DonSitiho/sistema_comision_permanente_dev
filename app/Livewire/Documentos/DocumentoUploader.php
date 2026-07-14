<?php

namespace App\Livewire\Documentos;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\CifradoService;
use App\Models\Sesion; 

class DocumentoUploader extends Component
{
    use WithFileUploads;

    public $archivo;
    public $categoria;
    public $sesionId;

    public function mount($sesion = null)
    {
        if ($sesion) {
            $this->sesionId = is_object($sesion) ? $sesion->id : $sesion;
        }
    }

    public function subir(CifradoService $cifradoService)
    {
        $this->validate([
            'archivo' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10 MB máx
        ]);

        if (!$this->sesionId) {
            $this->dispatch('error', 'No se ha detectado una sesión válida para subir el documento.');
            return;
        }
        $sesion = Sesion::find($this->sesionId);

        if (!$sesion) {
            $this->dispatch('error', 'La sesión especificada no existe en el sistema.');
            return;
        }

        $convocatoriaId = $sesion->convocatoria_id;
        $nombreOriginal = $this->archivo->getClientOriginalName();
        $nombreArchivo  = time() . '_' . $nombreOriginal;
        
        $rutaDinamica     = "convocatoria_{$convocatoriaId}/sesion_{$this->sesionId}/{$nombreArchivo}";
        $contenidoBinario = file_get_contents($this->archivo->getRealPath());

        try {
            //Cifra y guarda el archivo físico en el disco
            $rutaFinal = $cifradoService->cifrarYAlmacenar($contenidoBinario, $rutaDinamica);

            $sesion->documentos()->create([ 
                "nombre_original" => $nombreOriginal, 
                "ruta_cifrada"    => $rutaFinal, 
                "mime_type"        => $this->archivo->getMimeType(), 
                "tamano"           => $this->archivo->getSize(), 
                "subido_por"       => \Illuminate\Support\Facades\Auth::id(), 
                "categoria"        => $this->categoria ?: null, 
            ]);

            $this->dispatch('success', 'Documento cargado correctamente.');
            $this->reset(['archivo', 'categoria']);

        } catch (\Exception $e) {
            $this->dispatch('error', 'Fallo al almacenar el documento: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.documentos.documento-uploader');
    }
}