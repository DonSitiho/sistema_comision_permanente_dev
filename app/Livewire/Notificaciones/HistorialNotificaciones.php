<?php

namespace App\Livewire\Notificaciones;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class HistorialNotificaciones extends Component
{
    public function leerNotificacion(int $id)
    {
        $user = Auth::user();
        $notif = $user->notificaciones()->findOrFail($id);
        $notif->leida_at = now();
        $notif->save();

        $user->refresh();

        // Mandamos una señal directa a la campana para que cargue los datos en el modal
        $this->dispatch('notificacion-leida-externa', id: $id)->to(CentroNotificaciones::class);
    }

    public function render()
    {
        return view('livewire.notificaciones.historial-notificaciones', [
            'historial' => Auth::user()->notificaciones()->orderBy('created_at', 'desc')->get()
        ]);
    }
}