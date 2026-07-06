<?php

namespace App\Services;

use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\User;

class MensajeService
{
    public static function enviar(
        Conversacion $conversacion,
        User $emisor,
        string $contenido
    ): Mensaje {
        $mensaje = Mensaje::create([
            "conversacion_id" => $conversacion->id,
            "emisor_id" => $emisor->id,
            "contenido" => $contenido,
        ]);

        // El emisor marca su propio mensaje como leido al enviarlo
        $conversacion->participantes()
            ->where("user_id", $emisor->id)
            ->update(["ultimo_leido_at" => now()]);

        // Notificar a los demas participantes (no al emisor)
        $destinatarios = $conversacion->usuarios()
            ->where("users.id", "!=", $emisor->id)
            ->get();

        foreach ($destinatarios as $destinatario) {
            NotificacionService::enviar(
                destinatario: $destinatario,
                tipo: "mensaje",
                titulo: "Nuevo mensaje de {$emisor->name}",
                mensaje: substr($contenido, 0, 100),
            );
        }

        return $mensaje;
    }

    // Crea una conversacion individual, o la retorna si ya existe entre ambos
    public static function obtenerOCrearIndividual(User $a, User $b): Conversacion
    {
        $existente = Conversacion::where("tipo", "individual")
            ->whereHas("usuarios", fn($q) => $q->where("users.id", $a->id))
            ->whereHas("usuarios", fn($q) => $q->where("users.id", $b->id))
            ->first();

        if ($existente) {
            return $existente;
        }

        $conversacion = Conversacion::create([
            "tipo" => "individual",
            "creada_por" => $a->id,
        ]);

        $conversacion->usuarios()->attach([$a->id, $b->id]);

        return $conversacion;
    }

    // Crea un grupo; si viene de una sesion, auto-incluye a los asistentes
    public static function crearGrupo(
        string $nombre,
        User $creador,
        array $participanteIds,
        ?int $sesionId = null
    ): Conversacion {
        $conversacion = Conversacion::create([
            "tipo" => "grupal",
            "nombre" => $nombre,
            "sesion_id" => $sesionId,
            "creada_por" => $creador->id,
        ]);

        // El creador siempre es admin del grupo
        $conversacion->participantes()->create([
            "user_id" => $creador->id,
            "es_admin" => true,
        ]);

        foreach (array_unique($participanteIds) as $userId) {
            if ($userId === $creador->id) continue;
            $conversacion->participantes()->create(["user_id" => $userId]);
        }

        return $conversacion;
    }
}