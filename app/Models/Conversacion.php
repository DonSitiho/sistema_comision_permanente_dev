<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversacion extends Model
{
    protected $table = "f1com_conversaciones";

    protected $fillable = ["tipo", "nombre", "sesion_id", "creada_por"];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function creador()
    {
        return $this->belongsTo(User::class, "creada_por");
    }

    public function participantes()
    {
        return $this->hasMany(Participante::class);
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, "f1com_participantes")
            ->withPivot(["ultimo_leido_at", "es_admin"]);
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class)->orderBy("created_at");
    }

    // Nombre a mostrar: el del grupo, o el del otro participante si es individual
    public function nombreParaUsuario(int $userId): string
    {
        if ($this->tipo === "grupal") {
            return $this->nombre ?? "Grupo sin nombre";
        }

        $otro = $this->usuarios()->where("users.id", "!=", $userId)->first();
        return $otro?->name ?? "Conversacion";
    }
}