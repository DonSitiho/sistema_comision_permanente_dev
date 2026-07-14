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
        "fecha_sesion", "lugar", "estado", 
    ]; 
  
    protected $casts = ["fecha_sesion" => "datetime"]; 
  
    public function creador() 
    { 
        return $this->belongsTo(User::class, "creada_por"); 
    } 
  
    /*public function sesion() 
    { 
        return $this->hasOne(Sesion::class); 
        
    } */
    public function sesion(): HasOne
    {
        return $this->hasOne(Sesion::class, 'convocatoria_id')->latestOfMany();
    }
}
