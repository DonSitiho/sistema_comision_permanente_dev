<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = "f1ses_notas";
    protected $fillable = ["sesion_id", "autor_id", "tipo", "contenido"];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, "autor_id");
    }

    public function scopeNotas($query)
    {
        return $query->where("tipo", "nota");
    }

    public function scopeComentarios($query)
    {
        return $query->where("tipo", "comentario");
    }
}