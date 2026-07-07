<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    protected $table = "f1doc_documentos"; 
  
    protected $fillable = [ 
        "documentable_type", "documentable_id", 
        "nombre_original", "ruta_cifrada", "mime_type", 
        "tamano", "subido_por", "categoria", 
    ]; 
  
    public function documentable() 
    { 
        return $this->morphTo(); 
    } 
  
    public function subidoPor() 
    { 
        return $this->belongsTo(User::class, "subido_por"); 
    } 
}
