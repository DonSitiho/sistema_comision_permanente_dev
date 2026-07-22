<?php
// app/Models/Sesion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\services\AuditService;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Sesion extends Model
{
    protected $table = "f1ses_sesiones"; 
  
    protected $fillable = [ 
        "convocatoria_id", "tipo", "estado", 
        "enlace_videoconf", "plataforma", "creada_por", 
        "hora_inicio", "hora_fin", "descripcion_sesion", "num_enlace_videoconf", "cod_acceso_videoconf", 
    ]; 
  
    protected $casts = [ 
        "videoconf_metadata"      => "array", 
        "videoconf_sincronizado"  => "boolean", 
        'hora_inicio' => 'datetime:H:i',
        'hora_fin'    => 'datetime:H:i',
    ]; 
  
    public function convocatoria() { return $this->belongsTo(Convocatoria::class); } 
    public function creador()      { return $this->belongsTo(User::class, "creada_por"); } 
    public function asistentes()   { return $this->hasMany(Asistente::class); } 
    public function notas()        { return $this->hasMany(Nota::class); } 
    public function acuerdos()     { return $this->hasMany(Acuerdo::class); } 
  
    private const TRANSICIONES = [ 
        "convocada" => ["en_curso", "cancelada"], 
        "en_curso"  => ["realizada", "cancelada"], 
        "realizada" => [], 
        "cancelada" => [], 
    ]; 
  
    public function puedeTransicionarA(string $nuevoEstado): bool 
    { 
        return in_array($nuevoEstado, self::TRANSICIONES[$this->estado] ?? []); 
    } 
  
    public function transicionarA(string $nuevoEstado): void 
    { 
        if (!$this->puedeTransicionarA($nuevoEstado)) { 
            throw new \DomainException( 
                "No se puede pasar de {$this->estado} a {$nuevoEstado}." 
            ); 
        } 
  
        $estadoAnterior = $this->estado; 
        $this->update(["estado" => $nuevoEstado]); 
  
        AuditService::log("cambio_estado", "f1ses_sesiones", $this->id, [ 
            "de" => $estadoAnterior, 
            "a"  => $nuevoEstado, 
        ]); 
    } 
  
    public function requiereVideoconferencia(): bool 
    { 
        return in_array($this->tipo, ["virtual", "mixta"]); 
    } 

    public function documentos(): MorphMany
    {
        return $this->morphMany(Documento::class, 'documentable');
    }
}
