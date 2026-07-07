<?php 
// app/Services/CifradoService.php 
  
namespace App\Services; 
  
use Illuminate\Support\Facades\Crypt; 
use Illuminate\Support\Facades\Storage; 
  
class CifradoService 
{ 
    public function cifrarYAlmacenar(string $contenidoBinario, string $rutaDestino): string 
    { 
        $cifrado = Crypt::encrypt($contenidoBinario); 
        Storage::disk("documentos_cifrados")->put($rutaDestino, $cifrado); 
        return $rutaDestino; 
    } 
  
    public function retrieveAndDecryptFile(string $rutaCifrada): string 
    { 
        if (!Storage::disk("documentos_cifrados")->exists($rutaCifrada)) { 
            throw new \Exception("Archivo cifrado no encontrado: {$rutaCifrada}"); 
        } 
  
        $contenidoCifrado = Storage::disk("documentos_cifrados")->get($rutaCifrada); 
  
        try { 
            return Crypt::decrypt($contenidoCifrado); 
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) { 
            throw new \Exception("Error al descifrar. Posible corrupcion de datos."); 
        } 
    }
}