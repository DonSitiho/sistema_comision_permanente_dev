<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistente extends Model
{
    protected $table = "f1ses_asistentes"; 
    protected $fillable = ["sesion_id", "user_id", "asistio", "rol_en_sesion"]; 
    protected $casts = ["asistio" => "boolean"]; 
  
    public function sesion() { 
        return $this->belongsTo(Sesion::class); 
    }
    public function user()   { 
        return $this->belongsTo(User::class); 
    } 
} 
// app/Models/Nota.php 
namespace App\Models; 
class Nota extends Model 
{ 
    protected $table = "f1ses_notas"; 
    protected $fillable = ["sesion_id", "autor_id", "tipo", "contenido"]; 
} 
public function sesion() { 
    return $this->belongsTo(Sesion::class); 
} 
public function autor()  { 
    return $this->belongsTo(User::class, "autor_id"); 
} 
public function scopeNotas($query) { 
    return $query->where("tipo", "nota"); 
} 
public function scopeComentarios($query) { 
    return $query->where("tipo", "comentario"); 
} 
