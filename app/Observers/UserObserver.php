<?php
// app/Observers/UserObserver.php
 
namespace App\Observers;
 
use App\Models\User;
use App\Services\AuditService;
 
class UserObserver
{
    /**
     * Se dispara cuando se crea un usuario nuevo.
     * Registra nombre y email — nunca password.
     */
    public function created(User $user): void
    {
        AuditService::log(
            accion:    'created',
            entidad:   'users',
            entidadId: $user->id,
            valores:   [
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->roles->first()?->name,
            ]
        );
    }
 
    /**
     * Se dispara cuando se actualiza un usuario.
     * getDirty() retorna solo los campos que cambiaron.
     * getOriginal() retorna los valores anteriores al cambio.
     */
    public function updated(User $user): void
    {
        $cambios = $user->getDirty();
 
        // No loguear si solo cambió last_login_at/ip (lo hace el Listener)
        $ignorar = ['last_login_at', 'last_login_ip', 'remember_token'];
        $cambiosReales = array_diff_key($cambios, array_flip($ignorar));
 
        if (empty($cambiosReales)) {
            return;
        }
 
        // Construir snapshot before/after para los campos que cambiaron
        $before = [];
        $after  = [];
        foreach ($cambiosReales as $campo => $valorNuevo) {
            // Nunca guardar passwords en la bitácora
            if ($campo === 'password') {
                $before[$campo] = '[cifrado]';
                $after[$campo]  = '[cifrado]';
                continue;
            }
            $before[$campo] = $user->getOriginal($campo);
            $after[$campo]  = $valorNuevo;
        }
 
        AuditService::log(
            accion:    'updated',
            entidad:   'users',
            entidadId: $user->id,
            valores:   ['before' => $before, 'after' => $after]
        );
    }
 
    /**
     * Se dispara cuando se elimina (o desactiva) un usuario.
     */
    public function deleted(User $user): void
    {
        AuditService::log(
            accion:    'deleted',
            entidad:   'users',
            entidadId: $user->id,
            valores:   ['name' => $user->name, 'email' => $user->email]
        );
    }
}
