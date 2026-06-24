<?php
// app/Listeners/LogLogout.php
 
namespace App\Listeners;
 
use App\Services\AuditService;
use Illuminate\Auth\Events\Logout;
 
class LogLogout
{
    public function handle(Logout $event): void
    {
        AuditService::log(
            accion:    'logout',
            entidad:   'users',
            entidadId: $event->user?->id,
            userId:    $event->user?->id
        );
    }
}