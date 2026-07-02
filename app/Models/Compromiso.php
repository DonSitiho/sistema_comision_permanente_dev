<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Compromiso extends Model
{
    protected $table = "f1acu_compromisos";

    protected $fillable = [
        "acuerdo_id", "responsable_id", "descripcion",
        "fecha_limite", "estado",
    ];

    protected $casts = ["fecha_limite" => "date"];

    public function acuerdo()
    {
        return $this->belongsTo(Acuerdo::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, "responsable_id");
    }

    // Local scope: compromisos de un responsable especifico
    public function scopeDeResponsable(Builder $query, int $userId): Builder
    {
        return $query->where("responsable_id", $userId);
    }

    public function scopeVencidos(Builder $query): Builder
    {
        return $query
            ->whereIn("estado", ["pendiente", "en_proceso"])
            ->where("fecha_limite", "<", now());
    }

    public function marcarCumplido(): void
    {
        $this->update(["estado" => "cumplido"]);
        $this->acuerdo->recalcularEstado();
    }
}