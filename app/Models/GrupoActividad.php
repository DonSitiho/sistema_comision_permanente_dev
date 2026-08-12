<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoActividad extends Model
{
    //use HasFactory;
    protected $table = 'f2grp_grupos_actividades';
    protected $fillable = [
        'nombre','descripcion','dueno_id','convocatoria_id','acuerdo_id',
        'estatus','estatus_manual',
    ];
    protected $casts = ['estatus_manual' => 'boolean'];
 
    public function dueno()        { return $this->belongsTo(User::class, 'dueno_id'); }
    public function convocatoria() { return $this->belongsTo(Convocatoria::class); }
    public function acuerdo()      { return $this->belongsTo(Acuerdo::class); }
    public function actividades()  { return $this->hasMany(Actividad::class, 'grupo_id'); }
 
    public function recalcularEstatus(): void {
        if ($this->estatus_manual) return;
        $total      = $this->actividades()->count();
        $terminadas = $this->actividades()->where('estatus', 'terminado')->count();
        $iniciadas  = $this->actividades()->whereIn('estatus', ['en_proceso','terminado'])->count();
        $this->estatus = match (true) {
            $total === 0           => 'pendiente',
            $terminadas === $total => 'terminado',
            $iniciadas > 0         => 'en_proceso',
            default                => 'pendiente',
        };
        $this->save();
    }
}
