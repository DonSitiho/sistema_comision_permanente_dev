<?php

namespace App\Livewire\Notificaciones;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CentroNotificaciones extends Component
{
    public $notificacionSeleccionada = null;

    #[On('notificacion-nueva')]
    public function marcarLeida(int $id)
    {
        $user = Auth::user();
        $notif = $user->notificaciones()->findOrFail($id);
        $notif->leida_at = now(); 
        $notif->save(); 

        $this->notificacionSeleccionada = $notif;
        $user->refresh(); 

        $this->dispatch('mostrar-modal-notif');
    }

    // Escucha de forma global los eventos lanzados por otros componentes (para la vista del historial de notificaciones)
    #[On('notificacion-leida-externa')]
    public function refrescarContadorYModal(int $id)
    {
        $user = Auth::user();
        $this->notificacionSeleccionada = $user->notificaciones()->findOrFail($id);
        $user->refresh();

        $this->dispatch('mostrar-modal-notif');
    }

    public function marcarTodasLeidas()
    {
        $user = Auth::user();
        $user->notificacionesNoLeidas()->update(["leida_at" => now()]);
        $user->refresh();
    }

    public function verTodas()
    {
        return redirect()->route('historial-notificaciones');
    }
    
    public function render()
    {
        return view("livewire.notificaciones.centro-notificaciones", [
            "notificaciones" => Auth::user()->notificaciones()->orderBy('created_at', 'desc')->limit(5)->get(),
            "contador" => Auth::user()->notificacionesNoLeidas()->count(),
        ]);
    }
}