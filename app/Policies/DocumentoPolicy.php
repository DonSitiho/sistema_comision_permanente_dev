<?php
// app/Policies/DocumentoPolicy.php

namespace App\Policies;

use App\Models\Documento;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DocumentoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function before(User $user, string $ability): ?bool 
    { 
        if ($user->hasRole("Administrador")) return true; 
        return null; 
    } 
  
    public function view(User $user, Documento $documento): bool 
    { 
        $padre = $documento->documentable; 
  
        if ($padre instanceof App\Models\Sesion) { 
            return $user->can("view", $padre); 
        } 
        if ($padre instanceof App\Models\Acuerdo) { 
            return $user->can("view", $padre); 
        } 
  
        return $documento->subido_por === $user->id; 
    } 
  
    public function download(User $user, Documento $documento): bool 
    { 
        return $this->view($user, $documento); 
    } 
}
