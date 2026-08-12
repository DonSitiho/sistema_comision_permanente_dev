<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    //use HasFactory;
    protected $table = 'f2evi_evidencias';
    protected $fillable = ['actividad_id','tipo','documento_id','url','subida_por'];
 
    public function actividad() { return $this->belongsTo(Actividad::class, 'actividad_id'); }
    public function documento() { return $this->belongsTo(Documento::class, 'documento_id'); }
    public function autor()     { return $this->belongsTo(User::class, 'subida_por'); }
}
