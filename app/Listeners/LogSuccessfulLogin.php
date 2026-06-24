<?php
// app/Listeners/LogSuccessfulLogin.php
 
namespace App\Listeners;
 
use App\Services\AuditService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Request;
 
class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
 
        // saveQuietly() evita disparar el UserObserver (bucle infinito)
        $user->last_login_at = now();
        $user->last_login_ip = Request::ip();
        $user->saveQuietly();
 
        AuditService::log(
            accion:    'login',
            entidad:   'users',
            entidadId: $user->id,
            userId:    $user->id
        );
    }
}