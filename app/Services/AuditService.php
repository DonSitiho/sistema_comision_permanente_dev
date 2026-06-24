<?php
// app/Services/AuditService.php
 
namespace App\Services;
 
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
 
class AuditService
{
    /**
     * Registra una acción en la bitácora inmutable.
     *
     * @param string      $accion    Login, logout, created, updated, deleted...
     * @param string|null $entidad   Nombre de la entidad: "users", "dependencias"...
     * @param int|null    $entidadId ID del registro afectado.
     * @param array|null  $valores   Snapshot: ["before" => [...], "after" => [...]]
     * @param int|null    $userId    Si null, usa Auth::id() automáticamente.
     */
    public static function log(
        string  $accion,
        ?string $entidad    = null,
        ?int    $entidadId  = null,
        ?array  $valores    = null,
        ?int    $userId     = null
    ): void {
        // Nunca fallar silenciosamente — si el log falla, que sea visible
        AuditLog::create([
            // Usa el ID del usuario autenticado si no se pasa explícitamente.
            // Es null en intentos de login fallidos (no hay sesión).
            'user_id'    => $userId ?? Auth::id(),
            'accion'     => $accion,
            'entidad'    => $entidad,
            'entidad_id' => $entidadId,
            'valores'    => $valores,
            // Captura IP e User-Agent de la petición HTTP actual
            'ip'         => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}