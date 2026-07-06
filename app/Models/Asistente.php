<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistente extends Model
{
    protected $table = "f1ses_asistentes";
    protected $fillable = ["sesion_id", "user_id", "asistio", "rol_en_sesion"];
    protected $casts = ["asistio" => "boolean"];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}