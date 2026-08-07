<?php
namespace App\Policies;
use App\Models\Comunicado;
use App\Models\User;
class ComunicadoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole("Administrador")) return true;
        return null;
    }
    public function viewAny(User $user): bool
    {
        return $user->can("ver comunicados");
    }
    public function view(User $user, Comunicado $comunicado): bool
    {
        return $user->can("ver comunicados");
    }
    public function create(User $user): bool
    {
        return $user->can("crear comunicados");
    }
    public function update(User $user, Comunicado $comunicado): bool
    {
        if (!$user->can("editar comunicados")) return false;
        return $comunicado->estado === "borrador";
    }
    public function verLecturas(User $user, Comunicado $comunicado): bool
    {
        return $user->can("crear comunicados");
    }
}