<?php
// app/Models/Convocatoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Convocatoria extends Model
{
    protected $table = "f1ses_convocatorias"; 
  
    protected $fillable = [ 
        "folio", "creada_por", "titulo", "descripcion", 
        "fecha_sesion", "lugar", "rol_convocante","naturaleza","ambito","estado"//, "tipo_conv"
    ]; 
  
    protected $casts = ["fecha_sesion" => "datetime"]; 
  
    public function creador() 
    { 
        return $this->belongsTo(User::class, "creada_por"); 
    } 
  
    public function sesion(): HasOne
    {
        return $this->hasOne(Sesion::class, 'convocatoria_id')->latestOfMany();
    }
    /*public function sesiones() { 
        return $this->hasMany(Sesion::class, 'convocatoria_id'); 
    }*/
   /* public function invitados()
    {
        return $this->belongsToMany(User::class, 'f1ses_convocatoria_invitados')
                    ->withPivot(['rol_invitado', 'confirmado_at'])->withTimestamps();
    }*/

    public function acuerdos()
    {
        return $this->hasManyThrough(Acuerdo::class, Sesion::class, 
        'convocatoria_id', 'sesion_id', 'id', 'id'      
        );
    }

    public function scopeVisiblesPara($q, User $u) {
        if ($u->hasRole('Secretario Tecnico')) return $q; // admin: ve todas
        return $q->where(fn($q) => $q
            ->where('creada_por', $u->id)
            ->orWhereHas('invitados', fn($i) => $i->where('users.id', $u->id)));
    }

    public function sugerirInvitadosPorAmbito(array $regionIds = []) {
        return match ($this->ambito) {
            'regional', 'multi_region' => User::whereIn('region_id', $regionIds)->pluck('id'),
            'municipal'                => collect(), // sin municipio: seleccion manual
            default                    => collect(),
        };
    }
    
    public function sincronizarInvitados(array $userIds): void {
        $this->invitados()->sync($userIds);
    }
}
