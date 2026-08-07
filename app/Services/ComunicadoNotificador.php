<?php
namespace App\Services;
use App\Models\Comunicado;
use Illuminate\Support\Str;
class ComunicadoNotificador
{
    // Notifica a cada destinatario de un comunicado recien enviado.
    public function avisar(Comunicado $comunicado): void
    {
        $preview = Str::limit(strip_tags($comunicado->cuerpo), 50);

        foreach ($comunicado->destinatarios as $destinatario) {
            NotificacionService::enviar(
                destinatario: $destinatario,
                tipo: "comunicado",
                titulo: $comunicado->titulo,
                mensaje: $preview,
                urlDestino: route("comunicados") . "?abrir=" . $comunicado->id,
            );
        }
    }

    // Reenvia el aviso solo a quienes aun no han abierto el comunicado.
    public function recordarPendientes(Comunicado $comunicado): int
    {
        $pendientes = $comunicado->destinatarios()->wherePivotNull("leido_at")->get();
        $preview = Str::limit(strip_tags($comunicado->cuerpo), 50);

        foreach ($pendientes as $destinatario) {
            NotificacionService::enviar(
                destinatario: $destinatario,
                tipo: "comunicado_recordatorio",
                titulo: "Recordatorio: " . $comunicado->titulo,
                mensaje: $preview,
                urlDestino: route("comunicados") . "?abrir=" . $comunicado->id,
            );
        }

        return $pendientes->count();
    }
}