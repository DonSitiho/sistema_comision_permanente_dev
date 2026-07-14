<?php 
// app/Services/CifradoService.php 
  
namespace App\Services; 
  
use Illuminate\Support\Facades\Crypt; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Config;

class CifradoService 
{ 
    public function cifrarYAlmacenar(string $contenidoBinario, string $rutaDestino): string 
    { 
        if (!Config::has('filesystems.disks.documentos_cifrados')) {
            Config::set('filesystems.disks.documentos_cifrados', [
                'driver' => 'local',
                'root'   => storage_path('app/documentos_cifrados'),
                'throw'  => true,
            ]);
        }
        $cifrado = Crypt::encrypt($contenidoBinario);
        Storage::disk("documentos_cifrados")->put($rutaDestino, $cifrado);

        return $rutaDestino;
        /*$cifrado = Crypt::encrypt($contenidoBinario); 
        Storage::disk("documentos_cifrados")->put($rutaDestino, $cifrado); 
        return $rutaDestino; */
    } 
  
    public function retrieveAndDecryptFile(string $rutaCifrada): string
    {
        // Asegura que el disco exista también al intentar descargar/leer
        if (!Config::has('filesystems.disks.documentos_cifrados')) {
            Config::set('filesystems.disks.documentos_cifrados', [
                'driver' => 'local',
                'root'   => storage_path('app/documentos_cifrados'),
                'throw'  => true,
            ]);
        }

        if (!Storage::disk("documentos_cifrados")->exists($rutaCifrada)) {
            throw new \Exception("Archivo cifrado no encontrado: {$rutaCifrada}");
        }

        $contenidoCifrado = Storage::disk("documentos_cifrados")->get($rutaCifrada);

        try {
            return Crypt::decrypt($contenidoCifrado);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            throw new \Exception("Error al descifrar. Posible corrupción de datos.");
        }

        /* if (!Storage::disk("documentos_cifrados")->exists($rutaCifrada)) { 
            throw new \Exception("Archivo cifrado no encontrado: {$rutaCifrada}"); 
        } 
  
        $contenidoCifrado = Storage::disk("documentos_cifrados")->get($rutaCifrada); 
  
        try { 
            return Crypt::decrypt($contenidoCifrado); 
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) { 
            throw new \Exception("Error al descifrar. Posible corrupcion de datos."); 
        }  */
    }
}