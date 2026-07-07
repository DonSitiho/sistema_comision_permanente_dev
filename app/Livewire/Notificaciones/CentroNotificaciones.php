<?php
// app/Livewire/Notificaciones/CentroNotificaciones.php

namespace App\Livewire\Notificaciones;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CentroNotificaciones extends Component
{
    //protected $listeners = ["notificacion-nueva" => "$refresh"];
    #[On('notificacion-nueva')]
    public function marcarLeida(int $id): void
    {
        $notif = Auth::user()->notificaciones()->findOrFail($id);
        $notif->marcarLeida();
    }

    public function marcarTodasLeidas(): void
    {
        Auth::user()->notificacionesNoLeidas()->update(["leida_at" => now()]);
    }
    public function render()
    {

        return view("livewire.notificaciones.centro-notificaciones", [
            "notificaciones" => Auth::user()->notificaciones()->limit(15)->get(),
            "contador" => Auth::user()->notificacionesNoLeidas()->count(),
        ]);
    }
}
