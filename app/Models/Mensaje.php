<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Concerns\HasDocumentos; // TODO: descomentar cuando Dev 1 termine el modulo de Documentos

class Mensaje extends Model
{
    // use HasDocumentos; // TODO: descomentar cuando Dev 1 termine el modulo de Documentos

    protected $table = "f1com_mensajes";

    protected $fillable = ["conversacion_id", "emisor_id", "contenido"];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class);
    }

    public function emisor()
    {
        return $this->belongsTo(User::class, "emisor_id");
    }
}