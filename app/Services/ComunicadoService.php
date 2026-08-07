<?php
namespace App\Services;
use App\Models\Comunicado;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class ComunicadoService
{
    public function guardarBorrador(array $datos): Comunicado
    {
        $comunicado = isset($datos["id"])
            ? Comunicado::findOrFail($datos["id"])
            : new Comunicado();

        abort_if(
            $comunicado->exists && !$comunicado->editable(),
            422,
            "Este comunicado ya fue enviado y no se puede editar."
        );

        $comunicado->fill([
            "categoria_id" => $datos["categoria_id"],
            "emitido_por" => $datos["emitido_por"],
            "titulo" => $datos["titulo"],
            "cuerpo" => $datos["cuerpo"],
            "obligatorio" => $datos["obligatorio"] ?? false,
            "alcance" => $datos["alcance"],
            "criterio" => $datos["criterio"] ?? null,
        ]);

        $comunicado->estado = $comunicado->estado ?? "borrador";
        $comunicado->save();

        return $comunicado;
    }

    public function enviar(Comunicado $comunicado, array $criterio): void
    {
        abort_unless($comunicado->estado === "borrador", 422, "Este comunicado ya fue enviado.");

        $userIds = $this->expandirDestinatarios($criterio);

        DB::transaction(function () use ($comunicado, $criterio, $userIds) {
            $comunicado->update([
                "estado" => "enviado",
                "enviado_at" => now(),
                "criterio" => $criterio,
            ]);

            $comunicado->destinatarios()->sync($userIds);

            app(ComunicadoNotificador::class)->avisar($comunicado);
        });
    }

    // Traduce el criterio (general | region | lista) a IDs de usuario concretos.
    // "region" filtra usuarios cuya Dependencia pertenece a esa Region.
    public function expandirDestinatarios(array $criterio): array
    {
        return match ($criterio["alcance"] ?? null) {
            "region" => User::whereHas("dependencia", fn ($q) => $q->whereIn("region_id", $criterio["regionIds"] ?? []))
                ->pluck("id")->all(),
            "lista" => $criterio["userIds"] ?? [],
            default => User::pluck("id")->all(), // "general"
        };
    }

    public function marcarLeido(Comunicado $comunicado, User $usuario): void
    {
        $comunicado->destinatarios()->updateExistingPivot($usuario->id, ["leido_at" => now()]);
    }

    public function aceptar(Comunicado $comunicado, User $usuario): void
    {
        $comunicado->destinatarios()->updateExistingPivot($usuario->id, [
            "leido_at" => now(),
            "aceptado_at" => now(),
        ]);
    }
}