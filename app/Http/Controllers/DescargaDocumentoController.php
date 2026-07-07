<?php 
// app/Http/Controllers/DescargaDocumentoController.php 
  
namespace App\Http\Controllers; 
  
use App\Models\Documento; 
use App\Services\CifradoService; 
use Illuminate\Support\Facades\Gate; 
  
class DescargaDocumentoController extends Controller 
{ 
    public function __construct(private CifradoService $cifrado) {} 
  
    public function descargar(int $id) 
    { 
        $documento = Documento::findOrFail($id); 
        Gate::authorize("download", $documento); 
        $contenido = $this->cifrado->retrieveAndDecryptFile($documento->ruta_cifrada);  
        return response()->streamDownload(function () use ($contenido) { 
            echo $contenido; 
        }, $documento->nombre_original, ["Content-Type" => $documento->mime_type ?? "application/octet-stream",]);

    }
}
