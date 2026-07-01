<?php
// app/Jobs/EnviarCorreoNotificacionJob.php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\\Support\\Facades\\Mail;
use App\\Mail\\NotificacionMail;
use App\\Models\\Notificacion;

class EnviarCorreoNotificacionJob implements ShouldQueue
{
    use Queueable, dispatchable, InteractsWithQueue, SerializesModels;
    public int $tries = 3; // Número de intentos antes de marcar como fallido
    /**
     * Create a new job instance.
     */
    public function __construct(private Notificacion $notificacion)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $usuario = $this->notificacion->user;
        if (!$usuario->email_notificaciones) {
            return;
        }
        Mail::to($usuario->email_notificaciones)
        ->send(new NotificacionMail($this->notificacion));
    }
}
