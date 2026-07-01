<?php 
// app/Policies/SesionPolicy.php 
  
namespace App\Policies; 
  
use App\Models\Sesion; 
use App\Models\User; 
  
class SesionPolicy 
{ 
    public function before(User $user, string $ability): ?bool 
    { 
        if ($user->hasRole("Administrador")) return true; 
        return null; 
    } 
  
    public function viewAny(User $user): bool 
    { 
        return true; 
    } 
  
    public function view(User $user, Sesion $sesion): bool 
    { 
        if ($user->hasAnyRole(["Secretario Tecnico", "Auditor"])) return true; 
        if ($sesion->creada_por === $user->id) return true; 
        return $sesion->asistentes()->where("user_id", $user->id)->exists(); 
    } 
  
    public function create(User $user): bool 
    { 
        return true; 
    } 
  
    public function update(User $user, Sesion $sesion): bool 
    { 
        if ($user->hasRole("Secretario Tecnico")) return true; 
        return $sesion->creada_por === $user->id; 
    } 
  
    public function delete(User $user, Sesion $sesion): bool 
    { 
        return $this->update($user, $sesion); 
    } 
} 