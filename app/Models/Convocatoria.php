<?php
// app/Models/Convocatoria.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocatoria extends Model
{
    protected $table = "f1ses_convocatorias"; 
  
    protected $fillable = [ 
        "folio", "creada_por", "titulo", "descripcion", 
        "fecha_sesion", "lugar", "estado", 
    ]; 
  
    protected $casts = ["fecha_sesion" => "datetime"]; 
  
    public function creador() 
    { 
        return $this->belongsTo(User::class, "creada_por"); 
    } 
  
    public function sesion() 
    { 
        return $this->hasOne(Sesion::class); 
    } 
}
