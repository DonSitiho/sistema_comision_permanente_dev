<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use App\Models\Concerns\HasDocumentos; // TODO: descomentar cuando Dev 1 termine el modulo de Documentos


class Acuerdo extends Model
{
    //use HasDocumentos;/ TODO: descomentar cuando Dev 1 termine el modulo de Documentos

    protected $table = "f1acu_acuerdos";

    protected $fillable = ["sesion_id", "folio", "descripcion", "estado"];

    public function sesion()
    {
        return $this->belongsTo(Sesion::class);
    }

    public function compromisos()
    {
        return $this->hasMany(Compromiso::class);
    }

    // Maquina de estados, mismo patron que Sesion (ver guia Dev 1)
    private const TRANSICIONES = [
        "registrado" => ["en_proceso", "cancelado"],
        "en_proceso" => ["cumplido", "cancelado"],
        "cumplido" => [],
        "cancelado" => [],
    ];

    public function puedeTransicionarA(string $nuevoEstado): bool
    {
        return in_array($nuevoEstado, self::TRANSICIONES[$this->estado] ?? []);
    }

    public function transicionarA(string $nuevoEstado): void
    {
        if (!$this->puedeTransicionarA($nuevoEstado)) {
            throw new \DomainException(
                "No se puede pasar de {$this->estado} a {$nuevoEstado}."
            );
        }

        $estadoAnterior = $this->estado;
        $this->update(["estado" => $nuevoEstado]);

        \App\Services\AuditService::log("cambio_estado", "f1acu_acuerdos", $this->id, [
            "de" => $estadoAnterior,
            "a" => $nuevoEstado,
        ]);
    }

    // Se recalcula automaticamente cuando cambian los compromisos
    public function recalcularEstado(): void
{
    if ($this->compromisos()->where("estado", "!=", "cumplido")->doesntExist()
        && $this->compromisos()->exists()) {
        // Si venimos de "registrado", primero pasamos por "en_proceso"
        if ($this->estado === "registrado") {
            $this->transicionarA("en_proceso");
        }
        if ($this->estado === "en_proceso") {
            $this->transicionarA("cumplido");
        }
    } elseif ($this->compromisos()->where("estado", "en_proceso")->exists()) {
        if ($this->estado === "registrado") {
            $this->transicionarA("en_proceso");
        }
    }
}
}