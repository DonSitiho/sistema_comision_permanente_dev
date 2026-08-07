<?php
namespace App\Services;
use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\User;
use Illuminate\Http\UploadedFile;
class MensajeService
{
    public static function enviar(
        Conversacion $conversacion,
        User $emisor,
        string $contenido,
        ?UploadedFile $adjunto = null
    ): Mensaje {
        $mensaje = Mensaje::create([
            "conversacion_id" => $conversacion->id,
            "emisor_id" => $emisor->id,
            "contenido" => $contenido,
        ]);

        if ($adjunto) {
            $contenidoBinario = file_get_contents($adjunto->getRealPath());
            $rutaDestino = "mensaje_" . $mensaje->id . "/" . uniqid() . ".enc";
            $rutaFinal = app(CifradoService::class)->cifrarYAlmacenar($contenidoBinario, $rutaDestino);

            $mensaje->documentos()->create([
                "nombre_original" => $adjunto->getClientOriginalName(),
                "ruta_cifrada" => $rutaFinal,
                "mime_type" => $adjunto->getMimeType(),
                "tamano" => $adjunto->getSize(),
                "subido_por" => $emisor->id,
                "categoria" => "adjunto_chat",
            ]);
        }

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

    // Abre (o reutiliza) el hilo 1:1 entre dos usuarios.
    // clave_par = los dos IDs ordenados de menor a mayor: sin importar
    // quien inicia el chat, la clave siempre queda igual para ese par,
    // asi firstOrCreate nunca duplica el hilo.
    public static function iniciarIndividual(User $a, User $b): Conversacion
    {
        $clave = collect([$a->id, $b->id])->sort()->implode("-");

        $conversacion = Conversacion::firstOrCreate(
            ["clave_par" => $clave, "tipo" => "individual"],
            ["creado_por" => $a->id],
        );

        $conversacion->participantes()->firstOrCreate(["user_id" => $a->id]);
        $conversacion->participantes()->firstOrCreate(["user_id" => $b->id]);

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
            "tipo" => "grupo",
            "nombre" => $nombre,
            "sesion_origen_id" => $sesionId,
            "creado_por" => $creador->id,
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