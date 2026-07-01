<?php 
// app/Services/FolioService.php 
  
namespace App\Services; 
  
use App\Models\Convocatoria; 
use Illuminate\Support\Facades\DB; 
  
class FolioService 
{ 
    public static function generarConvocatoria(): string 
    { 
        return DB::transaction(function () { 
            $anio = now()->year; 
  
            $ultimo = Convocatoria::where("folio", "like", "CPC-CONV-{$anio}-%") 
                ->lockForUpdate() 
                ->orderByDesc("id") 
                ->first(); 
  
            $consecutivo = $ultimo 
                ? ((int) substr($ultimo->folio, -4)) + 1 
                : 1; 
  
            return sprintf("CPC-CONV-%d-%04d", $anio, $consecutivo); 
        }); 
    } 
} 