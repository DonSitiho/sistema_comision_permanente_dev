<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    protected $table = "f1com_participantes";

    protected $fillable = ["conversacion_id", "user_id", "ultimo_leido_at", "es_admin"];

    protected $casts = ["ultimo_leido_at" => "datetime", "es_admin" => "boolean"];

    public function conversacion()
    {
        return $this->belongsTo(Conversacion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function marcarLeido(): void
    {
        $this->update(["ultimo_leido_at" => now()]);
    }
}