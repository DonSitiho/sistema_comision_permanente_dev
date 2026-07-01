<?php
// app/Services/NotificacionService.php
 
namespace App\Services;
 
use App\Models\Notificacion;
use App\Models\User;
use App\Jobs\EnviarCorreoNotificacionJob;

class NotificacionService
{

    public static function enviar(
        User $destinatario,
        string $tipo,
        string $titulo,
        string $mensaje,
        ?string $urlDestino = null
    ): Notificacion {
        $notificacion = Notificacion::create([
            "user_id" => $destinatario->id,
            "tipo" => $tipo,
            "titulo" => $titulo,
            "mensaje" => $mensaje,
            "url_destino" => $urlDestino
        ]);

        // Enviar correo de notificación si el usuario tiene email configurado y verificado
        if ($destinatario->email_notificaciones && $destinatario->email_notif_verificado_at) {
            EnviarCorreoNotificacionJob::dispatch($notificacion);
        }

        return $notificacion;
    }
}