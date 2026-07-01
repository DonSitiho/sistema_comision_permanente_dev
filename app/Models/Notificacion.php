<?php
//app/Models/Notificacion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Notificacion extends Model
{
    protected $table = "f1not_notificaciones";
    protected $fillable = [
        "user_id", "tipo", "titulo", "mensaje", "url_destino"
    ];
    protected $casts = [
        "leida_at" => "datetime",
    ];

    //use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNoLeidas(Builder $query): Builder
    {
        return $query->whereNull("leida_at");
    }

    public function marcarLeida(): void
    {
        if(is_null($this->leida_at)){
            $this->update(["leida_at" => now()]);
        }
    }
}
