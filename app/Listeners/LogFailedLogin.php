<?php
// app/Listeners/LogFailedLogin.php
 
namespace App\Listeners;
 
use App\Services\AuditService;
use Illuminate\Auth\Events\Failed;
 
class LogFailedLogin
{
    public function handle(Failed $event): void
    {
        // user_id null: no hay sesión activa en un intento fallido.
        // El email intentado va en valores para auditoría.
        AuditService::log(
            accion:   'login_failed',
            entidad:  'users',
            valores:  [
                'email_intentado' => $event->credentials['email'] ?? null,
            ],
            userId:   null
        );
    }
}