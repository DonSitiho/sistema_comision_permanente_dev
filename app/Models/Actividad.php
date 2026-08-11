<?php
// App/Models/Actividad.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    protected $table = 'f2act_actividades'; // O 'actividades', según corresponda

    protected $fillable = [
        'grupo_id',
        //'convocatoria_id',
        //'acuerdo_id',
        'descripcion',
        'responsable_id',
        //'fecha_evidencia',
        'fecha_limite',
        'estatus',
    ];
    protected static function booted(): void
    {
        static::saved(fn (Actividad $a)   => $a->grupo?->recalcularEstatus());
        static::deleted(fn (Actividad $a) => $a->grupo?->recalcularEstatus());
    }
}
